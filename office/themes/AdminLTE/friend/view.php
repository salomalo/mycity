<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Friend */

$this->title = $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Friends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="friend-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'idUser',
                'value' => $model->user->username,
            ],
            [
                'attribute' => 'idFriend',
                'value' => $model->getUserFriend($model->idFriend)->username,
            ],
            [
                'attribute' => 'status',
                'value' => \common\models\User::$types[$model->status],
            ],
        ],
    ]) ?>

</div>
