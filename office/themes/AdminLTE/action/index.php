<?php

use common\models\Action;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Business;
use common\models\ActionCategory;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Action */
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
$btnAddAction = Html::a('Добавить Акцию', ['create','idCompany' => $business->id], ['class' => 'btn btn-success']) ;

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
    $('.right-side .content-header h1').append('$btnAddAction');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<h4 style="margin-top: -10px;"><?= Yii::t('app', 'Action') ?></h4>

<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'action'])?>

<div class="action-index">
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

            //'id',
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return '<a href="'. Url::to(['/action/view', 'id' => $model->id, 'idCompany' => $this->context->idCompany]) .'">' . $model->title . '</a>';
                },
            ],
            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => function ($model) {
                    return $model->category->title;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(ActionCategory::find()->all(),'id','title'),
                    'attribute' => 'idCategory',
                    'options' => [
                        'placeholder' => 'Выберите категорию ...',
                        'id' => 'idCategory',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                 'format' => 'html',
                'options' => ['width' => '120px'],
                'filter' => false,
                'value' => function ($model) {
                     if ($model->image) {
                         return Html::img(Yii::$app->files->getUrl($model, 'image', 100), ['style' => 'height: 100px']);
                     } else {
                         return '';
                     }
                },
            ],
            [
                'attribute' => 'dateStart',
                'label' => 'Дата начала',
                'value' => function ($model) {
                    if ($model->dateStart) {
                        $newformat = date('Y-m-d', strtotime($model->dateStart));
                        return $newformat;
                    } else {
                        return $model->dateStart;
                    }
                },
            ],
            [
                'attribute' => 'dateEnd',
                'label' => 'Дата окончания',
                'value' => function ($model) {
                    if ($model->dateStart) {
                        $newformat = date('Y-m-d', strtotime($model->dateEnd));
                        return $newformat;
                    } else {
                        return $model->dateEnd;
                    }
                },
            ],
            [
                'label' => 'Статус',
                'value' => function ($model) {
                    $currentDate = date('Y-m-d H:i:s');
                    if ($currentDate < $model->dateStart){
                        return Action::$statusList[Action::STATUS_WAIT];
                    } elseif ($currentDate > $model->dateEnd){
                        return Action::$statusList[Action::STATUS_END];
                    } else {
                        return Action::$statusList[Action::STATUS_LAUNCH];
                    }
                },
                'filter'  => Select2::widget([
                    'model' => $searchModel,
                    'data' => Action::$statusList,
                    'attribute' => 'status',
                    'options' => [
                        'placeholder' => 'Выберите статус ...',
                        'id' => 'statusId',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            (new yii\grid\ActionColumn())->createUrl('action/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                'title' => Yii::t('app', 'view'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            (new yii\grid\ActionColumn())->createUrl('action/update', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
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
