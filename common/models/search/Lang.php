<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lang as LangModel;

/**
 * Lang represents the model behind the search form about `common\models\Lang`.
 */
class Lang extends LangModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'default', 'date_update', 'date_create'], 'integer'],
            [['url', 'local', 'name'], 'safe'],
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
        $query = LangModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'default' => $this->default,
            'date_update' => $this->date_update,
            'date_create' => $this->date_create,
        ]);

        if($this->url) $query->andFilterWhere(['like', 'url', $this->url]);
        if($this->local) $query->andFilterWhere(['like', 'local', $this->local]);
        if($this->name) $query->andFilterWhere(['~~*', 'name', ('%'.$this->name.'%')]);

        return $dataProvider;
    }
}
