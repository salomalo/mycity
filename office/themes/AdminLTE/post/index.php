<?php

use common\models\Post;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\PostCategory;
use common\models\City;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Post */
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
$btnAddPost = Html::a('Добавить новость', ['create', 'idCompany' => $business->id], ['class' => 'btn btn-success']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
    $('.right-side .content-header h1').append('$btnAddPost');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="post-index">
    <h4 style="margin-top: -10px;"><?= Yii::t('business', 'Posts') ?></h4>

    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'post'])?>

    <div class="box-header with-border" style="border-top: 3px solid #d2d6de;background-color: #ffffff;">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Тариф <?= $business::$priceTypes[$business->price_type] ?></h3>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{items}\n{pager}\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width' => '70px'],
            ],
            [
                'attribute' => 'idCity',
                'label' => 'Город',
                'value' => function ($model) {
                    return ($model->city) ? $model->city->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => City::getAll(['id' => Yii::$app->params['activeCitys']]),
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
            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => function (Post $model) {
                    return $model->categoryTitle;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(PostCategory::find()->all(), 'id', 'title'),
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
                'attribute' => 'title',
                'format' => 'html',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return '<a href="'. Url::to(['/post/view', 'id' => $model->id, 'idCompany' => $this->context->idCompany]) .'">' . $model->title . '</a>';
                },
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    if ($model->image) {
                        return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 90) . ' " >';
                    } else return '';
                },
            ],
            [
                'attribute' => 'shortText',
                'label' => 'Краткое описание',
                'format' => 'html',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            (new yii\grid\ActionColumn())->createUrl('post/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'view'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            (new yii\grid\ActionColumn())->createUrl('post/update', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
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
