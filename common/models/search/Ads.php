<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ads as AdsModel;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveQuery;

/**
 * Description of Ads
 *
 * @author dima
 */
class Ads extends AdsModel
{
    public function behaviors()
    {
        return [];
    }
    
    public function rules()
    {
        return [
            [['_id', 'title', 'idCategory', 'idCity', 'idUser', 'isShowOnBusiness'], 'safe'],
        ];
    }

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
        return AdsModel::find()->with(['category', 'city', 'company']);
    }
    
    public function search($params)
    {
        $query = $this->getModelQuery();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['_id' => $this->_id]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        if ($this->idCategory) {
            $productCategory = ProductCategory::find()->where(['~~*', 'title', "%{$this->idCategory}%"])->all();
            $value = ArrayHelper::getColumn($productCategory, 'id');
            if (!empty($value)) {
                $query->andFilterWhere(['idCategory' => $value]);
            } else {
                //если не нашлись категории то пустой результат
                $query->andFilterWhere(['idCategory' => 0]);
            }
        }

        if ((int)$this->idCity) {
            $query->andFilterWhere(['idCity' => (int)$this->idCity]);
        }

        if ((int)$this->idUser) {
            $query->andFilterWhere(['idUser' => (int)$this->idUser]);
        }

        if ((int)$this->isShowOnBusiness) {
            if ((int)$this->isShowOnBusiness == Ads::DISPLAY_ON_BUSINESS) {
                $query->andFilterWhere(['isShowOnBusiness' => '1']);
            } else {
                $query->andFilterWhere(['!=', 'isShowOnBusiness', '1']);
            }
        }

        return $dataProvider;
    }
}
