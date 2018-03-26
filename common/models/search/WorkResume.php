<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WorkResume as WorkResumeModel;
use yii\db\ActiveQuery;

/**
 * WorkResume represents the model behind the search form about `common\models\WorkResume`.
 */
class WorkResume extends WorkResumeModel
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
            [['id', 'idCategory', 'idUser', 'idCity', 'male', 'isFullDay', 'isOffice'], 'integer'],
            [['title', 'description', 'name', 'year', 'experience', 'salary', 'phone', 'email', 'skype', 'dateCreate'], 'safe'],
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
        return WorkResumeModel::find()->with(['category', 'city', 'user']);
    }

    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @param bool $office
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getModelQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idCategory' => $this->idCategory,
            'idUser' => $this->idUser,
            'idCity' => $this->idCity,
            'male' => $this->male,
            'isFullDay' => $this->isFullDay,
            'isOffice' => $this->isOffice,
            'dateCreate' => $this->dateCreate,
        ]);

        if ($this->title) {
            $query->andFilterWhere(['~~*', 'title', "%{$this->title}%"]);
        }

        return $dataProvider;
    }
}
