<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessCustomField as BusinessCustomFieldModel;

/**
 * BusinessCustomField represents the model behind the search form about `common\models\BusinessCustomField`.
 */
class BusinessCustomField extends BusinessCustomFieldModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'multiple', 'filter_type'], 'integer'],
            [['title'], 'string'],
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
        $query = BusinessCustomFieldModel::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'multiple' => $this->multiple,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
