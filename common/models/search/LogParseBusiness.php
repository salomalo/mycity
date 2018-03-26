<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LogParseBusiness as LogParseBusinessModel;

/**
 * LogParseBusiness represents the model behind the search form about `common\models\LogParseBusiness`.
 */
class LogParseBusiness extends LogParseBusinessModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'isFail'], 'integer'],
            [['title', 'url', 'message'], 'safe'],
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
        $query = LogParseBusinessModel::find();

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
            'isFail' => $this->isFail,
        ]);

        if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        if($this->url) $query->andFilterWhere(['~~*', 'url', ('%'.$this->url.'%')]);
        if($this->message) $query->andFilterWhere(['~~*', 'message', ('%'.$this->message.'%')]);

        return $dataProvider;
    }
}
