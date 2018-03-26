<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Wall as WallModel;
use yii\db\ActiveQuery;

/**
 * Wall represents the model behind the search form about `common\models\Wall`.
 */
class Wall extends WallModel
{
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
            [['id', 'pid', 'type', 'idCity'], 'integer'],
            [['title', 'description', 'url', 'image', 'dateCreate'], 'safe'],
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
        return WallModel::find()->with(['city']);
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

        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            'type' => $this->type,
            'idCity' => $this->idCity,
            'dateCreate' => $this->dateCreate,
        ]);
        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->title}%"]);
        }
        if ($this->description) {
            $query->andFilterWhere(['~~*', 'description', "%{$this->description}%"]);
        }

        return $dataProvider;
    }
}
