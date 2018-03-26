<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ParserDomain as ParserDomainModel;

/**
 * ParserDomain represents the model behind the search form about `common\models\ParserDomain`.
 */
class ParserDomain extends ParserDomainModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idRegion', 'idCity'], 'integer'],
            [['domain', 'mDomain'], 'safe'],
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
        $query = ParserDomainModel::find()->with(['city', 'region']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idRegion' => $this->idRegion,
            'idCity' => $this->idCity,
        ]);

        if ($this->domain) {
            $query->andFilterWhere(['~~*', 'domain', ('%' . $this->domain . '%')]);
        }
        if ($this->mDomain) {
            $query->andFilterWhere(['~~*', 'mDomain', ('%' . $this->mDomain . '%')]);
        }

        return $dataProvider;
    }
}
