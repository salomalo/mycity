<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Afisha as AfishaModel;
use yii\db\ActiveQuery;

/**
 * Afisha represents the model behind the search form about `common\models\Afisha`.
 */
class Afisha extends AfishaModel
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
            [['id', 'rating', 'idCategory', 'year', 'isChecked'], 'integer'],
            [['idsCompany', 'title', 'description', 'dateStart', 'dateEnd', 'image', 'genre'], 'safe'],
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
        return AfishaModel::find()->with(['category'])->leftJoin('business', 'business.id = ANY(afisha."idsCompany")');
    }
    
    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @param bool $office
     * @param null $idCity
     * @return ActiveDataProvider
     */
    public function search($params, $idCity = null)
    {
        $query = $this->getModelQuery();

        if ($idCity) {
            $query->andFilterWhere(['business."idCity"' => (int)$idCity]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'afisha.id' => $this->id,
            'idCategory' => $this->idCategory,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'rating' => $this->rating,
            'year' => $this->year,
            'afisha.isChecked' => $this->isChecked,
        ]);
        
        if ($this->idsCompany) {
            $query->andWhere(['&&', 'idsCompany', "{{$this->idsCompany}}"]);
        }
        
        if ($this->genre) {
            $query->andWhere(['&&', 'genre', "{{$this->genre}}"]);
        }

        if ($this->title) {
            $query->andWhere(['~~*', 'afisha.title', "%{$this->title}%"]);
        }

        if ($this->description) {
            $query->andWhere(['~~*', 'afisha.description', "%{$this->description}%"]);
        }

        return $dataProvider;
    }
}
