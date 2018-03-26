<?php

use common\models\BusinessOwnerApplication;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusinessOwnerApplication */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Owner Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-owner-application-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width' => '50px'],
            ],
            [
                'attribute' => 'user_id',
                'value' => function (BusinessOwnerApplication $model) {
                    return $model->user ? $model->user->username : $model->user_id;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::getAll(),
                    'attribute' => 'user_id',
                    'options' => ['placeholder' => 'Выберите пользователя', 'id' => 'user_id'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'business_id',
                'value' => function (BusinessOwnerApplication $model) {
                    return $model->business ? $model->business->title : $model->business_id;
                },
                'filter' => Select2::widget([
                    'data' => $searchModel->business_id ? [$searchModel->business_id => $searchModel->business->title] : null,
                    'model' => $searchModel,
                    'attribute' => 'business_id',
                    'options' => [
                        'placeholder' => 'Найдите и выберите предприятие',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['business/ajax-search']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]),
            ],
            [
                'attribute' => 'status',
                'value' => function (BusinessOwnerApplication $model) {
                    return $model->statusLabel;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => BusinessOwnerApplication::$statuses,
                    'attribute' => 'status',
                    'options' => ['placeholder' => 'Выберите статус', 'id' => 'status'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'created_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>