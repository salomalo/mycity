<?php
namespace common\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product as ProductModel;

/**
 * Product represents the model behind the search form about `common\models\Product`.
 *
 * @property $idCategory array
 */
class Product extends ProductModel
{
    public function rules()
    {
        return [
            [['_id', 'title', 'idCategory', 'idCompany'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProductModel::find()->with('category');

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['_id' => $this->_id]);

        if ($this->title) {
            $query->andFilterWhere(['like', 'title', $this->title]);
        }

        if (!empty($this->idCategory) and is_array($this->idCategory)) {
            $cats_id = [];
            foreach ($this->idCategory as $filter_cat_id) {
                $cats[] = (int)$filter_cat_id;

//                /** @var ProductCategory $category_object */
//                $category_object = ProductCategory::find()->where(['id' => (int)$filter_cat_id])->one();
//
//                if ($category_object) {
//                    $cats_id += $category_object->children()->select('id')->column();
//                }
            }
            $query->andFilterWhere(['idCategory' => $cats]);
        }

        return $dataProvider;
    }
}
