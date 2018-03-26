<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrdersAds as OrdersAdsModel;

/**
 * OrdersAds represents the model behind the search form about `common\models\OrdersAds`.
 */
class OrdersAds extends OrdersAdsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'countAds', 'idBusiness', 'status'], 'integer'],
            [['idAds'], 'safe'],
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
        $query = OrdersAdsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            'countAds' => $this->countAds,
            'idBusiness' => $this->idBusiness,
            'status' => $this->status,
        ]);

        if($this->idAds) $query->andFilterWhere(['~~*', 'idAds', ('%'.$this->idAds.'%')]);

        return $dataProvider;
    }
}
