<?php
/**
 * @var $this \yii\web\View
 * @var $searchModel common\models\search\Afisha
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $business common\models\Business
 * @var $idCompany integer
 * @var $isFilm boolean
 */

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Business;
use kartik\widgets\DateTimePicker;
use yii\helpers\Url;

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);

if($isFilm){
    $btnAddAfisha = Html::a('Добавить фильм', ['create', 'idCompany' => $idCompany, 'isFilm' => $isFilm], ['class' => 'btn btn-success']);
} else {
    $btnAddAfisha = Html::a('Добавить афишу', ['create', 'idCompany' => $idCompany, 'isFilm' => $isFilm], ['class' => 'btn btn-success']);
}


$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
    $('.right-side .content-header h1').append('$btnAddAfisha');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="afisha-index">
    <h4 style="margin-top: -10px;"><?= Yii::t('afisha', 'Afisha') ?></h4>
    <?= $this->render('/business/top_block', ['id' => $idCompany, 'active' => 'afisha'])?>
    <div class="box-header with-border" style="border-top: 3px solid #d2d6de;background-color: #ffffff;">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Тариф <?= $business::$priceTypes[$business->price_type] ?></h3>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{items}\n{pager}\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width'=>'70px'],],
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return '<a href="'. Url::to(['/afisha/view', 'id' => $model->id, 'idCompany' => $this->context->idCompany]) .'">' . $model->title . '</a>';
                },
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                 'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 70) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'dateStart',
                'options' => ['width'=>'250px'],
                'label' => 'Время начала',
                'value' => function ($model) {
                    if ($model->dateStart) {
                        $newformat = date('Y-m-d', strtotime($model->dateStart));
                        return $newformat;
                    } else {
                        return $model->dateStart;
                    }
                },
                'filter' =>  DatePicker::widget([
                    'options' => ['placeholder' => 'Введите время ...',],
                    'name' =>'dateStart',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'todayHighlight' => true,
                        'language' => 'ru',
                        'format' => 'yyyy-mm-dd'
                    ],
                ]),
            ],
            [
                'attribute' => 'dateEnd',
                'options' => ['width'=>'250px'],
                'label' => 'Время окончания',
                'value' => function ($model) {
                    if ($model->dateStart) {
                        $newformat = date('Y-m-d', strtotime($model->dateEnd));
                        return $newformat;
                    } else {
                        return $model->dateEnd;
                    }
                },
                'filter' =>  DatePicker::widget([
                    'options' => ['placeholder' => 'Введите время ...'],
                    'name' =>'dateEnd',
                    'pluginOptions' => [
                        'autoclose'=>false,
                        'todayHighlight' => true,
                        'language' => 'ru',
                        'format' => 'yyyy-mm-dd'
                    ],
                ]),
            ],
            [
                'attribute' => 'price',
                'options' => ['width'=>'70px'],
                'value' => function ($model) {
                    if ($model->price) {
                        return $model->price;
                    } else {
                        return '';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            (new yii\grid\ActionColumn())->createUrl('afisha/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'view'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            (new yii\grid\ActionColumn())->createUrl('afisha/update', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'update'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            (new yii\grid\ActionColumn())->createUrl('afisha/delete', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'delete'),
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
