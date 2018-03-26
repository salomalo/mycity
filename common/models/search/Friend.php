<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Friend as FriendModel;
use common\models\User;

/**
 * Friend represents the model behind the search form about `common\models\Friend`.
 */
class Friend extends FriendModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idUser', 'idFriend', 'status'], 'integer'],
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
     * @param $params
     * @param bool $office
     * @param null $status
     * @return ActiveDataProvider
     */
    public function search($params, $office = false, $status = null)
    {
        $query = FriendModel::find()->with(['user'])->orderBy('id DESC');
        
        if($office){
            $condition = ['idUser'=>Yii::$app->user->id];
            if(!empty(User::$types[$status])){
                $condition['status'] = $status; 
            }
            $query = FriendModel::find()->where($condition);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'idFriend' => $this->idFriend,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
    
    public function searchOffer($params)
    {
        $query = FriendModel::find()->where(['idFriend'=>Yii::$app->user->id, 'status' => Account::TYPE_FRIEND_INVITED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'idFriend' => $this->idFriend,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
    
    public function searchAccount($params)
    {
        $query = User::find()->where('id <> '.Yii::$app->user->id);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//        $query->andFilterWhere([
//            'id' => $this->id,
//            'type' => $this->type,
//            'created_at' => $this->created_at,
//        ]);
        
        if(!empty($params['Friend']['idFriend'])){
            $user = User::find()->select(['username'])->where(['id'=>$params['Friend']['idFriend']])->one();
            $query->andFilterWhere(['like', 'username', $user->username]);
        }
        
        if(isset($params['Friend']['status']) && $params['Friend']['status'] != '' && $params['Friend']['status'] >= User::TYPE_FRIEND_INVITED){
            $model = FriendModel::find()->select(['idFriend'])->where(['idUser'=>Yii::$app->user->id, 'status' => $params['Friend']['status']])->all();
            
            $arrList = [];
            foreach ($model as $item){
                $arrList[] = $item->idFriend;
            }
           
            $query->andFilterWhere(['id' => $arrList,]);
        }

        return $dataProvider;
    }
}
