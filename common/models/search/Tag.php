<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tag as TagModel;

/**
 * Tag represents the model behind the search form about `common\models\Tag`.
 */
class Tag extends TagModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TagModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id'=>SORT_DESC],
            ]
        ]);

        $this->load($params);

        if ($this->validate()){
            $query->andFilterWhere(['id' => $this->id]);
            if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        }
        return $dataProvider;
    }
}
