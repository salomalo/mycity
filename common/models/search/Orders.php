<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders as OrdersModel;
use yii\db\ActiveQuery;

/**
 * Orders represents the model behind the search form about `common\models\Orders`.
 */
class Orders extends OrdersModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idUser', 'paymentType', 'dateCreate', 'status'], 'integer'],
            [['address'], 'safe'],
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
     * @return ActiveQuery
     */
    protected function getModelQuery()
    {
        return OrdersModel::find()->with(['city', 'user']);
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

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'idCity' => $this->idCity,
            'paymentType' => $this->paymentType,
            'status' => $this->status,
            'dateCreate' => $this->dateCreate,
        ]);

        if ($this->address) {
            $query->andFilterWhere(['~~*', 'address', "%{$this->address}%"]);
        }
        if ($this->phone) {
            $query->andFilterWhere(['~~*', 'phone', "%{$this->phone}%"]);
        }
        if ($this->fio) {
            $query->andFilterWhere(['~~*', 'fio', "%{$this->fio}%"]);
        }
        if ($this->description) {
            $query->andFilterWhere(['~~*', 'description', "%{$this->description}%"]);
        }

        return $dataProvider;
    }
}
