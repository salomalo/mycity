<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment as CommentModel;
use yii\db\ActiveQuery;

/**
 * Comment represents the model behind the search form about `common\models\Comment`.
 */
class Comment extends CommentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idUser', 'type', 'pid', 'parentId', 'rating', 'like', 'unlike'], 'integer'],
            [['text', 'dateCreate'], 'safe'],
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
        return CommentModel::find()->with(['user']);
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
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idUser' => $this->idUser,
            'type' => $this->type,
            'pid' => $this->pid,
            'parentId' => $this->parentId,
            'rating' => $this->rating,
            'like' => $this->like,
            'unlike' => $this->unlike,
            'dateCreate' => $this->dateCreate,
        ]);

        if ($this->text) {
            $query->andWhere(['~~*', 'text', "%{$this->text}%"]);
        }

        return $dataProvider;
    }
}
