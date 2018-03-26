<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LogParse as LogParseModel;

/**
 * LogParse represents the model behind the search form about `common\models\LogParse`.
 */
class LogParse extends LogParseModel
{
    public function rules()
    {
        return [
            [['id', 'idProduct', 'isFail'], 'integer'],
            [['message', 'url', 'dateCreate'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LogParseModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id'=>SORT_DESC],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'url' => $this->url,
            'idProduct' => $this->idProduct,
            'isFail' => $this->isFail,
            'dateCreate' => $this->dateCreate,
        ]);

        if($this->message) $query->andFilterWhere(['~~*', 'message', ('%'.$this->message.'%')]);

        return $dataProvider;
    }
}
