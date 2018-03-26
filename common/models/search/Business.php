<?php

namespace common\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Business as BusinessModel;
use yii\db\ActiveQuery;

/**
 * Business represents the model behind the search form about `common\models\Business`.
 */
class Business extends BusinessModel
{
    public $view;

    public function behaviors()
    {
        return [];
    }

    public function rules()
    {
        return [
            [['id', 'idUser', 'idCategories', 'idCity', 'type', 'isChecked', 'ratio', 'view'], 'integer'],
            [['title', 'description', 'site', 'phone', 'urlVK', 'urlFB', 'urlTwitter', 'email','skype', 'image', 'dateCreate', 'status_paid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @return ActiveQuery
     */
    protected function getModelQuery()
    {
        return BusinessModel::find()->joinWith(['city', 'user']);
    }

    public function search($params)
    {
        $query = $this->getModelQuery();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $view_cat = ViewCount::CAT_BUSINESS;
        if (Yii::$app->request->city) {
            $query_city = "AND view_count.city_id = \"idCity\"";
        } else {
            $query_city = "";
        }
        $count_views = "(SELECT COALESCE(SUM(view_count.value), 0) FROM view_count WHERE (view_count.category = {$view_cat} AND view_count.item_id = business.id {$query_city}))";

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'id',
                'dateCreate',
                'title',
                'ratio',
                'view' => [
                    'asc' => [$count_views => SORT_ASC],
                    'desc' => [$count_views => SORT_DESC],
                    'label' => 'Просмотры'
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
//        SELECT *
//        FROM (SELECT temp.item_id, SUM(value) AS sum_value FROM (
//        SELECT * FROM view_count where category = 101) as temp
//GROUP BY item_id) as foo
//where foo.sum_value  = 18;
        $query->andFilterWhere([
            'business.id' => $this->id,
            'business.idUser' => $this->idUser,
            'business.idCity' => $this->idCity,
            'business.type' => $this->type,
            'business.isChecked' => $this->isChecked,
            'business.ratio' => $this->ratio,
            //'view_count.count' => $this->view,
        ]);
        
        $query->andFilterWhere(['EXTRACT(YEAR FROM "dateCreate")' => $this->dateCreate]);

        if ($this->status_paid){
            $currentDate = date('Y-m-d H:i:s');

            $plusWeekDate = strtotime($this->due_date . ' + 1 week');
            $plusWeekDate = date('Y-m-d H:i:s',$plusWeekDate);

            $minusWeekDate = strtotime($this->due_date . ' - 1 week');
            $minusWeekDate = date('Y-m-d H:i:s',$minusWeekDate);
            switch ($this->status_paid) {
                case 1:
                    $query->andWhere([
                        'or',
                        ['business.price_type' => self::PRICE_TYPE_FREE],
                        ['>=', 'business.due_date', $currentDate]
                    ]);
                    break;
                case 2:
                    $query->andWhere(['>=', 'business.due_date', $minusWeekDate]);
                    $query->andWhere(['<', 'business.due_date', $currentDate]);
                    break;
                case 3:
                    $query->andWhere(['<', 'business.due_date', $minusWeekDate]);
                    break;
            }
        }

        if ($this->idCategories) {
            $query->andWhere(['@>', 'business.idCategories', "{{$this->idCategories}}"]);
        }

        $this->replaceBadCharForAll();
        if ($this->title) {
            $str_title = str_replace([' ', '  ', '   ', PHP_EOL], '%', $this->title);
            $query->andWhere(['~~*', 'business.title', "%{$str_title}%"]);
        }
        if ($this->description) {
            $query->andWhere(['~~*', 'business.description', "%{$this->description}%"]);
        }
        if ($this->shortDescription) {
            $query->andWhere(['~~*', 'business.shortDescription', "%{$this->shortDescription}%"]);
        }
        if ($this->tags) {
            $query->andWhere(['~~*', 'business.tags', "%{$this->tags}%"]);
        }
        if ($this->view){
            $query->innerJoin('(SELECT * 
FROM (SELECT temp.item_id, SUM(value) AS sum_value FROM (
SELECT * FROM view_count where category = :cur_category) as temp
GROUP BY temp.item_id) as foo
where foo.sum_value  = :view) result', 'business.id = result.item_id', [':view' => $this->view, ':cur_category' => ViewCount::CAT_BUSINESS]);
        }
        $this->replaceBadCharForAll(true);
        
        return $dataProvider;
    }

    private function replaceBadCharForAll($toSpace = false)
    {
        if ($this->title) {
            $this->title = ($toSpace) ? $this->replaceToSpace($this->title) : $this->replaceBadChar($this->title);
        }
        if ($this->description) {
            $this->description = ($toSpace) ?
                $this->replaceToSpace($this->description) : $this->replaceBadChar($this->description);
        }
        if ($this->shortDescription) {
            $this->shortDescription = ($toSpace) ?
                $this->replaceToSpace($this->shortDescription) : $this->replaceBadChar($this->shortDescription);
        }
        if ($this->tags) {
            $this->tags = ($toSpace) ? $this->replaceToSpace($this->tags) : $this->replaceBadChar($this->tags);
        }
    }
    
    private function replaceBadChar($string)
    {
        $string = strip_tags($string);
        $string = str_replace(['"', '\'', '»', '«'], '_', $string);
        $string = trim($string);
        return $string;
    }

    private function replaceToSpace($string)
    {
        $string = str_replace(['_', '  ', '   '], ' ', $string);
        return trim($string);
    }
}
