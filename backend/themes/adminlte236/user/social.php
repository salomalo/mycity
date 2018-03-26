<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = [
    'twitter' => 'https://twitter.com/intent/user?user_id=',
    'vkontakte' => 'https://vk.com/id',
    'facebook' => 'https://www.facebook.com/'
];
?>
<div class="user-view">
    <div class="row">
        <div class="col-md-2 col-md-offset-1">
            <?php if (isset($data->photo)): ?>
                <div><?= Html::img($data->photo)?></div>
            <?php endif; ?>
            <?php if (isset($data->profile_image_url)): ?>
                <div><?= Html::img($data->profile_image_url)?></div>
            <?php endif; ?>

            <h1><?= isset($model->username) ? $model->username : (isset($data->name) ? $data->name : Html::encode($this->title)) ?></h1>

            <p>
                <?= Html::a('Профиль', ['view', 'id'=>$model->user_id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
            </p>
        </div>
        <div class="col-md-4">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'user_id',
                    'label' => 'ID пользователя',
                    'value' => isset($model->user_id) ? $model->user_id : '',
                ],
                [
                    'attribute' => 'username',
                    'label' => 'Имя пользователя',
                    'value' => isset($model->username) ? $model->username : '',
                ],
                [
                    'attribute' => 'provider',
                    'label' => 'Соц. сеть',
                    'value' => isset($model->provider) ? $model->provider : '',
                ],
                [
                    'label' => 'Url соц. сети',
                    'value' =>
                        (isset($model->client_id) and isset($model->provider))
                            ? Html::a($model->client_id, $baseUrl[$model->provider].$model->client_id)
                            : (isset($model->client_id) ? $model->client_id : ''),
                    'format' => 'html'
                ],
                [
                    'attribute' => 'email',
                    'label' => 'E-mail',
                    'value' => isset($model->email) ? $model->email : '',
                    'format' => 'email'
                ],
            ],
        ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <h1>Полученные данные пользвателя:</h1>

            <?= DetailView::widget([
                'model' => $data,
                'attributes' => $dataAttr,
            ]) ?>
        </div>
    </div>
</div>
