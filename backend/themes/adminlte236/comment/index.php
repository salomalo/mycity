<?php

use common\models\Comment;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Comment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <p>
        <?= Html::a('Create Comment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'value' => function (Comment $model) {
                    return Html::a($model->id, $model->entityUrl, ['target' => '_blank']);
                },
                'format' => 'html',
                'options' => ['width' => '80px'],
            ],
            [
                'attribute' => 'idUser',
                'value' => function (Comment $model) {
                    $link = null;
                    if (!empty($model->user)) {
                        $url = Url::to(['user/view', 'id' => $model->user->id]);
                        $link = Html::a($model->user->username, $url);
                    }
                    return $link;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::getAll(),
                    'attribute' => 'idUser',
                    'options' => [
                        'placeholder' => 'Select a user ...',
                        'id' => 'idUser',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'text',
                'value' => function (Comment $model) {
                    return html_entity_decode(strip_tags($model->text),ENT_QUOTES);
                },
            ],
            [
                'attribute' => 'type',
                'value' => function (Comment $model) {
                    $url = $model->getBackendUrlForType();
                    $link = Html::a($model->getType($model->type), $url);
                    return $link;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Comment::$types,
                    'attribute' => 'type',
                    'options' => [
                        'placeholder' => 'Select a type ...',
                        'id' => 'type',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'pid',
                'value' => function (Comment $model) {
                    $url = $model->getBackendUrlForPid();
                    $label = ($model->pidMongo) ? $model->pidMongo : $model->pid;
                    $link = Html::a($label, $url);
                    return $link;
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'dateCreate',
                'format' => 'datetime',
                'options' => ['width' => '180px']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
