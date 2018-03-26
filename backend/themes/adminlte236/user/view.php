<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a(Yii::t('user', 'Btn_update_a'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'Btn_delete_a'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('user', 'Are_you_sure_you_want_to_delete_this_item'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('user', 'Btn_create'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('user', 'Btn_index_a'), ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'confirmed_at:date',
            'blocked_at:date',
            'registration_ip',
            'created_at:date',
            'role',
            'city.title:text:Город',
            'last_activity:datetime',
            [
                'attribute' => 'utm_source',
                'value' => isset($model->userRegInfo->utm_source) ? $model->userRegInfo->utm_source : '',
            ],
            [
                'attribute' => 'utm_campaing',
                'value' => isset($model->userRegInfo->utm_campaing) ? $model->userRegInfo->utm_campaing : '',
            ],
            [
                'label' => 'Профиль:',
                'value' => '------------------',
            ],
            [
                'attribute' => 'Name',
                'value' => $model->name,
            ],
            [
                'attribute' => 'Public Email',
                'value' => $model->public_email,
            ],
            [
                'attribute' => 'Site',
                'value' => $model->website,
            ],
            [
                'attribute' => 'Bio',
                'value' => $model->bio,
            ],
            [
                'attribute' => 'Телефон',
                'value' => $model->phone,
            ],
        ],
    ]) ?>
    <p class="backend-social-btn">
        <?php if ($model->social_account):
            foreach ($model->social_account as $key => $item):
                $btnName = isset($item->provider) ?$item->provider : 'Соц. аккаунт';
                echo Html::a($btnName, ['social', 'id' => $item->id], ['class' => 'btn btn-primary']);
            endforeach;
        endif;?>
    </p>
</div>
