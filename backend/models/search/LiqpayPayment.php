<?php
namespace backend\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LiqpayPayment as LiqpayPaymentModel;

/**
 * LiqpayPayment represents the model behind the search form about `common\models\LiqpayPayment`.
 */
class LiqpayPayment extends LiqpayPaymentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['status', 'order_id', 'action', 'amount', 'currency', 'data'], 'safe'],
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
        $query = LiqpayPaymentModel::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'action' => $this->action,
            'status' => $this->status,
        ]);

        if ($this->order_id) {
            $query->andFilterWhere(['~~*', 'order_id', "%{$this->order_id}%"]);
        }

        if ($this->data) {
            $query->andFilterWhere(['~~*', 'data', "%{$this->data}%"]);
        }

        return $dataProvider;
    }
}
