<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Counter as CounterModel;

/**
 * Counter represents the model behind the search form about `common\models\Counter`.
 */
class Counter extends CounterModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'title'], 'string'],
            [['id', 'for_office', 'for_main', 'for_all_cities'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = CounterModel::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'for_office' => $this->for_office,
            'for_main' => $this->for_main,
            'for_all_cities' => $this->for_all_cities,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
