<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductCustomfield as ProductCustomfieldModel;

/**
 * ProductCustomfield represents the model behind the search form about `common\models\ProductCustomfield`.
 */
class ProductCustomfield extends ProductCustomfieldModel
{
    public function rules()
    {
        return [
            [['id', 'idCategory', 'type', 'isFilter'], 'integer'],
            [['title', 'alias'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProductCustomfieldModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idCategory' => $this->idCategory,
            'type' => $this->type,
            'isFilter' => $this->isFilter,
        ]);

        return $dataProvider;
    }
}
