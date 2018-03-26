<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AfishaCategory as AfishaCategoryModel;

/**
 * AfishaCategory represents the model behind the search form about `common\models\AfishaCategory`.
 */
class AfishaCategory extends AfishaCategoryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'isFilm'], 'integer'],
            [['title', 'image'], 'safe'],
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
        $query = AfishaCategoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            'isFilm' => $this->isFilm,
        ]);
        
        if($this->title) $query->andFilterWhere(['~~*', 'title', ('%'.$this->title.'%')]);
        if($this->image) $query->andFilterWhere(['~~*', 'image', ('%'.$this->image.'%')]);

        return $dataProvider;
    }
}
