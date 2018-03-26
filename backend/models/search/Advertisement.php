<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Advertisement as AdvertisementModel;
use yii\db\ActiveQuery;

/**
 * Advertisement represents the model behind the search form about `common\models\Advertisement`.
 */
class Advertisement extends AdvertisementModel
{
    public $time_status;

    public function behaviors()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'position', 'status', 'time_status', 'city_id'], 'integer'],
            [['title', 'image', 'url', 'date_start', 'date_end', 'created_at'], 'safe'],
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
        return AdvertisementModel::find()->with(['user', 'city']);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'city_id' => $this->city_id,
            'position' => $this->position,
            'status' => $this->status,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'created_at' => $this->created_at,
        ]);

        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->title}%"]);
        }
        if ($this->url) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->url}%"]);
        }
        $query->andFilterWhere(['like', 'image', $this->image]);

        $now = date('Y-m-d');
        switch ($this->time_status) {
            case AdvertisementModel::TIME_STATUS_ACTIVE:
                $query->andWhere(['<=', 'date_start', $now])->andWhere(['>=', 'date_end', $now]);
                break;
            case AdvertisementModel::TIME_STATUS_DONE:
                $query->andWhere(['<', 'date_end', $now]);
                break;
            case AdvertisementModel::TIME_STATUS_WAIT:
                $query->andWhere(['>', 'date_start', $now]);
                break;
        }

        return $dataProvider;
    }
}
