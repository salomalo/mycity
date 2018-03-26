<?php

use common\models\QuestionConversation;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\QuestionConversation */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выберите беседу';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-conversation-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать беседу', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Все вопросы', ['question/index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function(QuestionConversation $model){
                    return $model->statusLabel;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => QuestionConversation::$statuses,
                    'attribute' => 'status',
                    'options' => ['placeholder' => 'Выберите статус'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'title',
                'value' => function(QuestionConversation $model){
                    return Html::a("<span class='fa fa-comments'></span> $model->title", ['/question/index', 'Question[conversation_id]' => $model->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'object_type',
                'value' => function(QuestionConversation $model){
                    return $model->typeLabel;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => QuestionConversation::$object_types,
                    'attribute' => 'object_type',
                    'options' => ['placeholder' => 'Выберите тип'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'object_id',
            'created_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

