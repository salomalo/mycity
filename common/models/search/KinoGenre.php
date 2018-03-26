<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\KinoGenre as KinoGenreModel;

/**
 * KinoGenre represents the model behind the search form about `common\models\KinoGenre`.
 */
class KinoGenre extends KinoGenreModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'url'], 'safe'],
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
        $query = KinoGenreModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        if($this->url) $query->andFilterWhere(['~~*', 'url', ('%'.$this->url.'%')]);

        return $dataProvider;
    }
}
