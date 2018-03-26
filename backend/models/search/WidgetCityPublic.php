<?php

namespace backend\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WidgetCityPublic as WidgetCityPublicModel;
use yii\db\ActiveQuery;

/**
 * VkWidgetCityPublic represents the model behind the search form about `common\models\WidgetCityPublic`.
 */
class WidgetCityPublic extends WidgetCityPublicModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'width', 'height'], 'integer'],
            [['group_id', 'network'], 'string'],
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
        return WidgetCityPublicModel::find()->joinWith(['city'])
            ->andWhere(['city_id' => Yii::$app->params['adminCities']['id']]);
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
            'city_id' => $this->city_id,
            'width' => $this->width,
            'height' => $this->height,
            'network' => $this->network,
        ]);

        if ($this->group_id) {
            $query->andFilterWhere(['~~*', 'group_id', ('%'.$this->group_id.'%')]);
        }

        return $dataProvider;
    }
}
