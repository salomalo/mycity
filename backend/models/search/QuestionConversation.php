<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuestionConversation as QuestionConversationModel;

/**
 * QuestionConversation represents the model behind the search form about `common\models\QuestionConversation`.
 */
class QuestionConversation extends QuestionConversationModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'object_type'], 'integer'],
            [['title', 'object_id', 'created_at'], 'safe'],
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
        $query = QuestionConversationModel::find()->joinWith('user')
            ->where(['user.city_id' => Yii::$app->params['adminCities']['id']]);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'object_type' => $this->object_type,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'object_id', $this->object_id]);

        if ($this->title) {
            $query->andWhere(['~~*', 'title', "%{$this->title}%"]);
        }
        
        return $dataProvider;
    }
}

