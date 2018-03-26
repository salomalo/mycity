<?php

use common\models\Question;
use common\models\QuestionConversation;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Question */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;

$get = Yii::$app->request->get('Question');
$conversation_id = isset($get['conversation_id']) ? $get['conversation_id'] : null;
?>
<div class="question-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Создать', ['question/create', 'conversation_id' => $conversation_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Беседы', ['question-conversation/index'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user_id',
                'value' => function (Question $model) {
                    return ($model->user_id and $model->user) ? $model->user->username : null;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::getAll(),
                    'attribute' => 'user_id',
                    'options' => ['placeholder' => 'Выберите пользователя'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'conversation_id',
                'value' => function (Question $model) {
                    return ($model->conversation_id and $model->conversation) ? $model->conversation->title : null;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => QuestionConversation::getAll(),
                    'attribute' => 'conversation_id',
                    'options' => ['placeholder' => 'Выберите беседу'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'text:ntext',
            'created_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

