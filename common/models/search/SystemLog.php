<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SystemLog as SystemLogModel;

/**
 * SystemLog represents the model behind the search form about `common\models\SystemLog`.
 */
class SystemLog extends SystemLogModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['dateCreate', 'description','status'], 'safe'],
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
        $query = SystemLogModel::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'id',
                'dateCreate',
                'status',
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'system_log.id' => $this->id,
            'date("dateCreate")' =>$this->dateCreate,
            'system_log.status' => $this->status,
        ]);

        return $dataProvider;
    }
}
