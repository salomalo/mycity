<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessCategory as BusinessCategoryModel;

/**
 * BusinessCategory represents the model behind the search form about `common\models\BusinessCategory`.
 */
class BusinessCategory extends BusinessCategoryModel
{
    public function rules()
    {
        return [
            [['id', 'pid'], 'integer'],
            [['title', 'image'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = BusinessCategoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
        ]);

        if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        if($this->image) $query->andFilterWhere(['~~*', 'image', ('%'.$this->image.'%')]);

        return $dataProvider;
    }
}
