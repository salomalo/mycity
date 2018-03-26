<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Action as ActionModel;
use yii\db\ActiveQuery;

/**
 * Action represents the model behind the search form about `common\models\Action`.
 */
class Action extends ActionModel
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
            [['id', 'idCompany', 'idCategory'], 'integer'],
            [['title', 'description', 'dateStart', 'dateEnd', 'image', 'status'], 'safe'],
            [['price'], 'number'],
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
        return ActionModel::find()->leftJoin('business', 'business.id = action."idCompany"');
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
            'action.id' => $this->id,
            'idCompany' => $this->idCompany,
            'idCategory' => $this->idCategory,
            'price' => $this->price,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
        ]);
        
        if ($this->title) {
            $query->andWhere(['~~*', 'action.title', "%{$this->title}%"]);
        }

        if ($this->status){
            $currentDate = date('Y-m-d H:i:s');
            switch ($this->status) {
                case 1:
                    $query->andWhere(['>', 'action.dateStart', $currentDate]);
                    break;
                case 2:
                    $query->andWhere(['<', 'action.dateStart', $currentDate]);
                    $query->andWhere(['>', 'action.dateEnd', $currentDate]);
                    break;
                case 3:
                    $query->andWhere(['<', 'action.dateEnd', $currentDate]);
                    break;
            }
        }

        if ($this->description) {
            $query->andWhere(['~~*', 'action.description', "%{$this->description}%"]);
        }

        return $dataProvider;
    }
}
