<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>
    <?php
    if (isset($model->entity)) {
        $url = 'http://' . $model->entity->getSubDomain() . Yii::$app->params['appFrontend']
            . $model->entity->getFrontendUrl() . '#comments';
        $front_link = Html::a($model->id, $url, ['target' => '_blank']);
    } else {
        $front_link = '';
    }

    $user_link = null;
    if (!empty($model->user)) {
        $user_link = Html::a($model->user->username, Url::to(['user/view', 'id' => $model->user->id]));
    }

    $type_link = Html::a($model->getType($model->type), $model->getBackendUrlForType());

    $label = ($model->pidMongo) ? $model->pidMongo : $model->pid;
    $pid_link = Html::a($label, $model->getBackendUrlForPid());
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'value' => $front_link,
                'format' => 'html',
                'options' => ['width'=>'80px'],
            ],
            [
                'attribute' => 'idUser',
                'value' => $user_link,
                'format' => 'html',
            ],
            'text:html',
            [
                'attribute' => 'type',
                'value' => $type_link,
                'format' => 'html',
            ],
            [
                'attribute' => 'pid',
                'value' => $pid_link,
                'format' => 'html',
            ],
            'parentId',
            'like',
            'unlike',
            'lastIpLike',
            'rating',
            'ratingCount',
            'lastIpRating',
            'dateCreate:datetime',
        ],
    ]) ?>

</div>
