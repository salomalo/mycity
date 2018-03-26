<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessProductCategory as BusinessProductCategoryModel;

/**
 * BusinessProductCategory represents the model behind the search form about `common\models\BusinessProductCategory`.
 */
class BusinessProductCategory extends BusinessProductCategoryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'business_category_id', 'product_category_id'], 'integer'],
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
        $query = BusinessProductCategoryModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'business_category_id' => $this->business_category_id,
            'product_category_id' => $this->product_category_id,
        ]);

        return $dataProvider;
    }
}
