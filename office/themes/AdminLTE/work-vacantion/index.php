<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use common\models\City;
use common\models\Business;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkVacantion */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $business common\models\Business */

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);
$btnAddWorkVacantion = Html::a('Добавить вакансию', ['create', 'idCompany' => $business->id], ['class' => 'btn btn-success']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
    $('.right-side .content-header h1').append('$btnAddWorkVacantion');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="work-vacantion-index">
    <h4 style="margin-top: -10px;"><?= Yii::t('business', 'Job') ?></h4>

    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'work-vacantion'])?>
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
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return '<a href="'. Url::to(['/work-vacantion/view', 'id' => $model->id, 'idCompany' => $this->context->idCompany]) .'">' . $model->title . '</a>';
                },
            ],
            
            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => function ($model) {
                    return $model->category->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
                    'attribute' => 'idCategory',
                    'options' => [
                            'placeholder' => 'Выберите категорию ...',
                            'id' => 'idCategory',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idCity',
                'label' => 'Город',
                'value' => function ($model) {
                    return $model->city->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->all(),'id','title'),
                    'attribute' => 'idCity',
                    'options' => [
                            'placeholder' => 'Выберите город ...',
                            'id' => 'idCity',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],

//            'idCity',
            //'idUser',
            // 'description:ntext',
            // 'proposition:ntext',
            // 'phone',
            // 'email:email',
            // 'skype',
            // 'name',
            // 'salary',
            // 'isFullDay',
            // 'isOffice',
            // 'experience:ntext',
            // 'male',
            // 'minYears',
            // 'maxYears',
            // 'dateCreate',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            (new yii\grid\ActionColumn())->createUrl('work-vacantion/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'view'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            (new yii\grid\ActionColumn())->createUrl('work-vacantion/update', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
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
