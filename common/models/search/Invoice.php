<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoice as InvoiceModel;
use yii\db\ActiveQuery;

/**
 * Transactions represents the model behind the search form about `common\models\Transactions`.
 */
class Invoice extends InvoiceModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'object_type', 'object_id', 'paid_status'], 'integer'],
            [['paid_from', 'paid_to', 'created_at'], 'safe'],
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
        $query = $this->getModelQuery();

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
            'user_id' => $this->user_id,
            'object_type' => $this->object_type,
            'object_id' => $this->object_id,
            'paid_from' => $this->paid_from,
            'paid_to' => $this->paid_to,
            'created_at' => $this->created_at,
            'paid_status' => $this->paid_status,
        ]);

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    protected function getModelQuery()
    {
        return \common\models\Invoice::find()->with(['user']);
    }
}
