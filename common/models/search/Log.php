<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Log as LogModel;

/**
 * Description of Log
 *
 * @author dima
 */
class Log extends LogModel{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'description', 'dateCreate', 'operation', 'object_id', 'user_id', 'admin_id', 'ip', 'type'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LogModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if (!isset($params['allLogs'])){
            $query->andFilterWhere(['is not', 'user_id', null]);
        }
        
//        $query->andFilterWhere(["EXTRACT(YEAR FROM \"dateCreate\")" => $this->dateCreate]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'operation', $this->operation])
            ->andFilterWhere(['like', 'object_id', $this->object_id])
            ->andFilterWhere(['user_id' => $this->user_id])
            ->andFilterWhere(['admin_id' => $this->admin_id])
            ->andFilterWhere(['type' => $this->type])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['date("dateCreate")' =>$this->dateCreate]);
        
        return $dataProvider;
    }
}
