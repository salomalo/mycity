<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WorkVacantion as WorkVacantionModel;
use yii\db\ActiveQuery;

/**
 * WorkVacantion represents the model behind the search form about `common\models\WorkVacantion`.
 */
class WorkVacantion extends WorkVacantionModel
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
            [['id', 'idCompany', 'idCategory', 'idCity', 'idUser', 'isFullDay', 'isOffice', 'male'], 'integer'],
            [['title', 'description', 'proposition', 'phone', 'email', 'skype', 'name', 'salary', 'experience', 'minYears', 'maxYears', 'dateCreate'], 'safe'],
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
        return WorkVacantionModel::find()->with(['category', 'city', 'user']);
    }

    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getModelQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idCompany' => $this->idCompany,
            'idCategory' => $this->idCategory,
            'idCity' => $this->idCity,
            'idUser' => $this->idUser,
            'isFullDay' => $this->isFullDay,
            'isOffice' => $this->isOffice,
            'male' => $this->male,
            'dateCreate' => $this->dateCreate,
        ]);

        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->title}%"]);
        }

        return $dataProvider;
    }
}
