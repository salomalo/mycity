<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\User;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <p><?= Html::a(Yii::t('user', 'Create'), ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            'username',
            [
                'attribute' => 'name',
                'options' => ['width'=>'170px'],
                'value' => function ($model) {
                    return isset($model->profile->name) ? Html::decode($model->profile->name) : '';
                },
            ],
            'email:email',
            [
                'attribute' => 'role',
                'options' => ['width'=>'170px'],
                'value' => function ($model) {
                    return isset(User::$roles[$model->role]) ? User::$roles[$model->role] : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::$roles,
                    'attribute' => 'role',
                    'options' => [
                        'placeholder' => 'Select a role ...',
                        'id' => 'role',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'created_at',
                'options' => ['width'=>'170px'],
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]),
            ],
            [
                'attribute' => 'registration_ip',
                'options' => ['width'=>'120px'],
            ],
            [
                'attribute' => 'last_activity',
                'format' => 'date',
                'options' => ['width'=>'170px'],
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'last_activity',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => ['class' => 'form-control'],
                ]),
            ],
            [
                'attribute' => 'utm_source',
                'value' => function ($model) {
                    $userRegInfo = \common\models\UserRegInfo::find()->where(['user_id' => $model->id])->one();
                    if (isset($userRegInfo->utm_source)){
                        return $userRegInfo->utm_source;
                    } else {
                        return '';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70px'],
            ],
        ],
    ]); ?>
</div>
