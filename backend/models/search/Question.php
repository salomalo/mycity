<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Question as QuestionModel;

/**
 * Question represents the model behind the search form about `common\models\Question`.
 */
class Question extends QuestionModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'conversation_id'], 'integer'],
            [['text', 'created_at'], 'safe'],
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
        $query = QuestionModel::find()->joinWith('user')
            ->where(['user.city_id' => Yii::$app->params['adminCities']['id']]);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'conversation_id' => $this->conversation_id,
            'created_at' => $this->created_at,
        ]);

        if ($this->text) {
            $query->andWhere(['~~*', 'text', "%{$this->text}%"]);
        }

        return $dataProvider;
    }
}

