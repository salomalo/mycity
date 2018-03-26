<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ViewCount as ViewCountModel;

/**
 * ViewCount represents the model behind the search form about `common\models\ViewCount`.
 */
class ViewCount extends ViewCountModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'category', 'value', 'year', 'month', 'city_id'], 'integer'],
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
        $query = ViewCountModel::find()->with(['city', 'model']);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->city_id) {
            $query->andWhere(['city_id' => ((int)$this->city_id === -1) ? null : $this->city_id]);
        }

        $query->andFilterWhere([
            'id'        => $this->id,
            'item_id'   => $this->item_id,
            'category'  => $this->category,
            'value'     => $this->value,
            'year'      => $this->year,
            'month'     => $this->month,
        ]);

        return $dataProvider;
    }
}
