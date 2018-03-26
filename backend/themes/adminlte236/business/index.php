<?php

use common\models\Business;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessCategory;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Business $searchModel
 */

$this->title = Yii::t('business', 'Businesses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-index" id="business-index">
    
    <p><?= Html::a(Yii::t('business', 'Create_busi'), ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'idCity',
                'value' => function (Business $model) {
                    return ($model->city) ? $model->city->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => [
                        'placeholder' => 'Select a city ...',
                        'id' => 'idCity',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            'title:html',
            [
                'attribute' => 'idUser',
                'options' => ['width' => '135px'],
                'value' => function (Business $model) {
                    return isset($model->user->username) ? $model->user->username : $model->idUser;
                },
            ],
            [
                'attribute' => 'idCategories',
                'options' => ['width'=>'250px'],
                'value' => function (Business $model) {
                    return $model->categoryNames($model->idCategories);
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => BusinessCategory::getCategoryList(),
                    'attribute' => 'idCategories',
                    'options' => [
                        'placeholder' => 'Select a category ...',
                        'id' => 'idCategories',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'dateCreate',
                'options' => ['width'=>'160px'],
                'value' => function (Business $model) {
                    return date('d.m.Y, H:i:s', strtotime($model->dateCreate));
                },
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Поиск по году'
                 ]

            ],
            [
                'attribute' => 'isChecked',
                'options' => ['width' => '90px'],
                'value' => function(Business $model){
                    return ($model->isChecked) ? 'Да' : 'Нет';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [false => 'Нет', true => 'Да'],
                    'attribute' => 'isChecked',
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'ratio',
                'options' => ['width'=>'90px'],
            ],
            [
                'attribute' => 'view',
                'value' => function (Business $model) {
                    return $model->getViews();
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                    $arr1 = ['business/index'];
                    $arr = array_merge($arr1, Yii::$app->request->getQueryParams());
                    $arr['idDel'] = $model->id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    Yii::$app->urlManager->createUrl($arr),
                    [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax'=>'w0'
                    ]);
                    }
                ]
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width'=>'60px'],
                'checkboxOptions' => function($model, $key, $index, $column) {
                      return ['value' => $model->id , 'class' => 'selection_ids'];
                },
                'footer'=> Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/business/index'], [
                    'class' => 'btn btn-danger deletelist',
                    'title' => Yii::t('yii', 'Delete selected'),
                    'data-url' => Url::to(['/business/delete-list']),
                    'data-pjax' => 'w0',
                ])
            ],
        ],
    ]); ?>
</div>
