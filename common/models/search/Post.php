<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post as PostModel;
use yii\db\ActiveQuery;

/**
 * Post represents the model behind the search form about `common\models\Post`.
 */
class Post extends PostModel
{
    public function behaviors()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idUser', 'idCategory', 'idCity', 'status', 'business_id'], 'integer'],
            [['title', 'shortText', 'fullText', 'address', 'dateCreate', 'allCity', 'onlyMain'], 'safe'],
            [['lat', 'lon'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
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
        return PostModel::find()->with(['city', 'user', 'category']);
    }

    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @param bool $office
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getModelQuery();

        /** @var ActiveQuery $query */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'idCategory' => $this->idCategory,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'dateCreate' => $this->dateCreate,
            'idCity' => $this->idCity,
            'status' => $this->status,
            'allCity' => $this->allCity,
            'onlyMain' => $this->onlyMain,
            'business_id' => $this->business_id,
        ]);
        
        if($this->title) {
            $query->andFilterWhere(['~~*', 'title', ('%' . $this->title . '%')]);
        }
        if($this->shortText) {
            $query->andFilterWhere(['~~*', 'shortText', ('%' . $this->shortText . '%')]);
        }
        if($this->fullText) {
            $query->andFilterWhere(['~~*', 'fullText', ('%' . $this->fullText . '%')]);
        }
        if($this->address) {
            $query->andFilterWhere(['~~*', 'address', ('%' . $this->address . '%')]);
        }

        return $dataProvider;
    }
}
