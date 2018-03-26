<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WorkCategory as WorkCategoryModel;

/**
 * WorkCategory represents the model behind the search form about `common\models\WorkCategory`.
 */
class WorkCategory extends WorkCategoryModel
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WorkCategoryModel::find();

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);

        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', ('%' . $this->title . '%')]);
        }

        return $dataProvider;
    }
}
