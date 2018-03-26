<?php

use common\models\Ads;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use common\extensions\nestedSelect;

/**
 * @var yii\web\View $this
 * @var common\models\search\Product $searchModel
 * @var integer $idCompany
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var $business common\models\Business
 */

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);
$btnAddAds = Html::a(Yii::t('ads', 'Form_add'), ['create', 'idBusiness' => $idCompany], ['class' => 'btn btn-success']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
    $('.right-side .content-header h1').append('$btnAddAds');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="product-index">
    <h4 style="margin-top: -10px;"><?= Yii::t('ads', 'Ads') ?></h4>

    <?= $this->render('/business/top_block', ['id' => $idCompany, 'active' => 'ads'])?>
    
    <div class="box-header with-border" style="border-top: 3px solid #d2d6de;background-color: #ffffff;">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Тариф <?= $business::$priceTypes[$business->price_type] ?></h3>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'title',
                'label'=>'Заголовок',
                'format' => 'raw',
                'value' => function (Ads $model) {
                    return Html::a($model->title, ['view', 'id' => (string)$model->_id, 'idCompany' => $this->context->idCompany]);
                }
            ],
            [
                'attribute' => 'Company, model',
                'value' => function ($model) {
                    return ($model->company) ? $model->company->title.' '.(($model->tovar)? $model->tovar->model : '') : ''.' '.$model->model;
                },
                'label'=>'Предприятие, Модель',
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
                'label'=>'Картинка',
            ],
            [
                'attribute' => 'idCategory',
                'value' => 'category.title',
                'options' => ['width'=>'350px'],
                'filter' => nestedSelect::widget([
                    'model' => $searchModel,
                    'attribute' => 'idCategory',
                    'options' => [
                            'placeholder' => 'Выберите категорию ...',
                            'id' => 'idCategory',
                        'multiple' =>false,
                            ],
                    'pluginOptions' => [
//                            'allowClear' => true,
                    ]
                ]),
                'label'=>'Категория',

            ],
            [
                'attribute'=>'price',
                'label'=>'Цена',                
            ],
            [
                'label' => 'Приоритет на странице предприятия',
                'attribute' => 'isShowOnBusiness',
                'value' => function (Ads $model) {
                    return $model->isShowOnBusiness ?
                        Ads::$statusDisplayOnBusiness[Ads::DISPLAY_ON_BUSINESS] :
                        Ads::$statusDisplayOnBusiness[Ads::NOT_DISPLAY_ON_BUSINESS];
                },
//                'format' => 'html',
//                'value' => function (Business $model) {
//                    return $model->getStatusPaid();
//                },
                'filter'  => Select2::widget([
                    'model' => $searchModel,
                    'data' => Ads::$statusDisplayOnBusiness,
                    'attribute' => 'isShowOnBusiness',
                    'options' => [
                        'placeholder' => 'Выберите приоритет ...',
                        'id' => 'statusId',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute'=>'dateCreate',
                'label'=>'Время создания ',                
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            (new yii\grid\ActionColumn())->createUrl('ads/view', $model, ['id' => (string)$model->_id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'view'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            (new yii\grid\ActionColumn())->createUrl('ads/update', $model, ['id' => (string)$model->_id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'update'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
