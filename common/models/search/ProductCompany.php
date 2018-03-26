<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductCompany as ProductCompanyModel;

/**
 * ProductCompany represents the model behind the search form about `common\models\ProductCompany`.
 */
class ProductCompany extends ProductCompanyModel
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
        $query = ProductCompanyModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->id) $query->andFilterWhere(['id' => $this->id]);
        if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        if($this->image) $query->andFilterWhere(['~~*', 'image', ('%'.$this->image.'%')]);

        return $dataProvider;
    }
}
