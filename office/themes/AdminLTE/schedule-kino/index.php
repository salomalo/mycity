<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Business;
use common\models\Afisha;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ScheduleKino */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $idCompany integer */

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>
<div class="schedule-kino-index">
    <?= $this->render('/business/top_block', ['id' => $idCompany, 'active' => 'afisha'])?>

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить расписание к фильму', ['schedule-kino/create', 'idCompany' => $idCompany], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Список фильмов', ['afisha/list-films', 'idCompany' => $idCompany], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'idAfisha',
                'value' => function ($model) {
                    return ($model->afisha)? $model->afisha->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(Afisha::find()->where(['isFilm' => 1])->all(),'id','title'),
                    'attribute' => 'idAfisha',
                    'options' => [
                            'placeholder' => 'Select afisha ...',
                            'id' => 'idAfisha',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'format' => 'html',
                'options' => ['width'=>'80px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->afisha->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model->afisha, 'image', 70) . ' " >';
                     }
                     else return '';
                },
            ],
//            [
//                'attribute' => 'idCompany',
//                'value' => function ($model) {
//                    return ($model->company)? $model->company->title : '';
//                },
//                'filter' => Select2::widget([
//                    'model' => $searchModel,
//                    'data' => ArrayHelper::map(Business::find()->all(),'id','title'),
//                    'attribute' => 'idCompany',
//                    'options' => [
//                            'placeholder' => 'Select a company ...',
//                            'id' => 'idCompany',
//                            //'multiple' => true,
//                            ],
//                    'pluginOptions' => [
//                            'allowClear' => true,
//                    ]
//                ]),
//            ],

            [
                'attribute' => 'times',
                'options' => ['width'=>'100px'],
            ],
            [
                'attribute' => 'price',
                'options' => ['width'=>'100px'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}{update}{delete}',
                'buttons' => [
                            'view' => function ($url, $model) {
                                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                    (new yii\grid\ActionColumn())->createUrl('schedule-kino/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                        'title' => Yii::t('yii', 'view'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]);
                            },
                            'update' => function ($url, $model) {
                                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                    (new yii\grid\ActionColumn())->createUrl('schedule-kino/update', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                        'title' => Yii::t('yii', 'update'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]);
                            },
                            'delete' => function ($url, $model) {
                                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                    (new yii\grid\ActionColumn())->createUrl('schedule-kino/delete', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                        'title' => Yii::t('yii', 'delete'),
                                        'data-method' => 'post',
                                        'aria-label' => 'Delete',
                                        'data-confirm' => 'Are you sure you want to delete this item?',
                                        'data-pjax' => '0',
                                    ]);
                            },
                        ]
            ],
        ],
    ]); ?>

</div>
