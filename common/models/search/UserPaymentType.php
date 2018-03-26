<?php

namespace common\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserPaymentType as UserPaymentTypeModel;

/**
 * UserPaymentType represents the model behind the search form about `common\models\UserPaymentType`.
 */
class UserPaymentType extends UserPaymentTypeModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'payment_type_id'], 'integer'],
            [['description', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = UserPaymentTypeModel::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'payment_type_id' => $this->payment_type_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
