<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ParseKino as ParseKinoModel;

/**
 * ParseKino represents the model behind the search form about `common\models\ParseKino`.
 */
class ParseKino extends ParseKinoModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'remote_cinema_id', 'local_cinema_id'], 'integer'],
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
     * @return \yii\db\ActiveQuery
     */
    protected function getModelQuery()
    {
        return ParseKinoModel::find()->select(['parse_kino.*'])->leftJoin('business', 'business.id = parse_kino.local_cinema_id');
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
        $cookies = Yii::$app->request->cookies;

        if ($cookies->has('SUBDOMAINID')) {
            $query->andWhere(['business."idCity"' => $cookies->get('SUBDOMAINID')]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'parse_kino.id' => $this->id,
            'parse_kino.remote_cinema_id' => $this->remote_cinema_id,
            'parse_kino.local_cinema_id' => $this->local_cinema_id,
        ]);

        return $dataProvider;
    }
}
