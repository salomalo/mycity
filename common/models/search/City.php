<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\City as CityModel;

/**
 * City represents the model behind the search form about `common\models\City`.
 */
class City extends CityModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idRegion', 'main'], 'integer'],
            [['title', 'code', 'subdomain'], 'safe'],
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
        $query = CityModel::find()->with(['region'])->orderBy('id ASC');

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idRegion' => $this->idRegion,
            'main' => $this->main,
        ]);
        
        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', ('%' . $this->title . '%')]);
        }
        if ($this->code) {
            $query->andFilterWhere(['~~*', 'code', ('%' . $this->code . '%')]);
        }
        if ($this->subdomain) {
            $query->andFilterWhere(['~~*', 'subdomain', ('%' . $this->subdomain . '%')]);
        }

        return $dataProvider;
    }
}
