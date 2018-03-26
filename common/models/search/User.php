<?php

namespace common\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User as UserModel;

/**
 * User represents the model behind the search form about `common\models\User`.
 */
class User extends UserModel
{
    const DAY_END = ' 23:59:59';
    const DAY_START = ' 00:00:01';
    const ANY_STRING = '%';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'flags', 'role', 'level'], 'integer'],
            [['username', 'name', 'email', 'unconfirmed_email', 'registration_ip', 'created_at', 'updated_at', 'confirmed_at', 'blocked_at'], 'string'],
            [['last_activity', 'phone'], 'safe'],
//            'createdDefault' => [['name'], 'default', 'value' => null],
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
        $query = UserModel::find()->with('profile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'username',
                'name',
                'email',
                'role',
                'created_at',
                'registration_ip',
                'last_activity',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'flags' => $this->flags,
            'role' => $this->role,
        ]);
        if ($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', strtotime(($this->created_at . self::DAY_START)), strtotime(($this->created_at . self::DAY_END))]);
        }
        if ($this->last_activity) {
            $query->andFilterWhere(['between', 'last_activity', ($this->last_activity . self::DAY_START), ($this->last_activity . self::DAY_END)]);
        }
        if ($this->username) {
            $query->andFilterWhere(['~~*', 'username', $this->substrSearch($this->username)]);
        }
        if ($this->email) {
            $query->andFilterWhere(['~~*', 'email', $this->substrSearch($this->email)]);
        }
        if ($this->registration_ip) {
            $query->andFilterWhere(['~~*', 'registration_ip', $this->substrSearch($this->registration_ip)]);
        }

        return $dataProvider;
    }

    private function substrSearch($str)
    {
        return self::ANY_STRING . $str . self::ANY_STRING;
    }
}
