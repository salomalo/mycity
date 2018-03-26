<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Profile as ProfileModel;

/**
 * Profile represents the model behind the search form about `common\models\Profile`.
 */
class Profile extends ProfileModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name', 'public_email', 'gravatar_email', 'gravatar_id', 'location', 'website', 'bio'], 'safe'],
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
        $query = ProfileModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->name) $query->andFilterWhere(['~~*', 'name', ('%'.$this->name.'%')]);
        if($this->public_email) $query->andFilterWhere(['~~*', 'public_email', ('%'.$this->public_email.'%')]);
        if($this->location) $query->andFilterWhere(['~~*', 'location', ('%'.$this->location.'%')]);
        if($this->website) $query->andFilterWhere(['~~*', 'website', ('%'.$this->website.'%')]);
        if($this->bio) $query->andFilterWhere(['~~*', 'bio', ('%'.$this->bio.'%')]);

        return $dataProvider;
    }
}