<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ticket as TicketModel;
use yii\db\ActiveQuery;

/**
 * Ticket represents the model behind the search form about `common\models\Ticket`.
 */
class Ticket extends TicketModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idUser', 'status','pid','type', 'idCity'], 'integer'],
            [['title', 'email', 'dateCreate','pid','type'], 'safe'],
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
        return TicketModel::find()->with(['city', 'user']);
    }

    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getModelQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'idCity' => $this->idCity,
            'status' => $this->status,
            'dateCreate' => $this->dateCreate,
        ]);

        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->title}%"]);
        }

        return $dataProvider;
    }
}
