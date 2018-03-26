<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var common\models\Account $model
 */

$this->title = "Профиль пользователя - ".$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            //'password_hash',
            'type',
            'photoUrl:url',
            'vkID',
            'vkToken',
            [
                'attribute' => 'birthday',
                'value' => (extension_loaded('intl'))? 
                    Yii::t('user', '{0, date, MMMM dd, YYYY}', [strtotime($model->birthday)]) :
                    date('Y-m-d G:i:s', strtotime($model->birthday)),
            ],
            'description:html',
            'role',
            'status',
            [
                'attribute' => 'created_at',
                'value' => (extension_loaded('intl'))? 
                    Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]) :
                    date('Y-m-d G:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => (extension_loaded('intl'))? 
                    Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->updated_at]) :
                    date('Y-m-d G:i:s', $model->updated_at),
            ],
        ],
    ]) ?>

</div>
