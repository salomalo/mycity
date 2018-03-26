<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductCategory as ProductCategoryModel;

/**
 * ProductCategory represents the model behind the search form about `common\models\ProductCategory`.
 */
class ProductCategory extends ProductCategoryModel
{
    public function rules()
    {
        return [
            [['id','root','depth','lft','rgt'], 'integer'],
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
        $query = ProductCategoryModel::find();

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
