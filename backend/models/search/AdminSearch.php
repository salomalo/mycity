<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Admin;

/**
 * Account represents the model behind the search form about `common\models\Account`.
 */
class AdminSearch extends Admin
{

    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
            [['username', 'password_hash', 'dateCreate'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $office = false)
    {
        $query = Admin::find();
        
        if($office){
            $query->where(['id' => Yii::$app->user->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('dateCreate DESC'),
            'pagination' => ($office) ? false : ['pageSize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'level' => $this->level,
        ]);

        if ($this->dateCreate) {
            $query->andFilterWhere(['>=', 'dateCreate', ($this->dateCreate . ' 00:00:01')]);
            $query->andFilterWhere(['<=', 'dateCreate', ($this->dateCreate . ' 23:59:59')]);
        }
        if ($this->username) $query->andFilterWhere(['~~*', 'username', ('%'.$this->username.'%')]);

        return $dataProvider;
    }
}
