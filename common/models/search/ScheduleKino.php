<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ScheduleKino as ScheduleKinoModel;
use yii\db\ActiveQuery;

/**
 * ScheduleKino represents the model behind the search form about `common\models\ScheduleKino`.
 */
class ScheduleKino extends ScheduleKinoModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idAfisha', 'idCompany', 'idCity'], 'integer'],
            [['times', 'price', 'dateStart', 'dateEnd'], 'safe'],
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
        return ScheduleKinoModel::find()->with('company', 'city', 'afisha', 'afisha.afishaWeekRepeat');
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idAfisha' => $this->idAfisha,
            'idCompany' => $this->idCompany,
            'idCity' => $this->idCity,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
        ]);

        if ($this->times) {
            $query->andFilterWhere(['~~*', 'times', "%{$this->times}%"]);
        }

        return $dataProvider;
    }
}
