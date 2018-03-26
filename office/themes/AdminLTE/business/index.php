<?php

use common\components\LiqPay\LiqPayStatuses;
use common\models\File;
use common\models\Invoice;
use common\models\LiqpayPayment;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessCategory;
use common\models\Business;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Business $searchModel
 */

$this->title = Yii::t('business', 'My_Businesses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-index">
    <p>
        <?= Html::a(Yii::t('business', 'New_Businesses'), ['pay'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => '№№',
                'options' => ['width'=>'80px'],
            ],
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
                'format' => 'html',
                'value' => function (Business $model) {
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                }
            ],
            [
                'attribute' => 'price_type',
                'label' => 'Выбранный тариф',
                'value' => function (Business $model) {
                    $value = '';
                    if (isset(Business::$priceTypes[$model->price_type])) {
                        $value = Business::$priceTypes[$model->price_type];
                    }
                    if ($value and isset(Business::$prices[$model->price_type])) {
                        $value .= ' - ' . Business::$prices[$model->price_type] . ' грн.';
                    }
                    return $value;
                }
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                'format' => 'html',
                'filter' =>false,
                'options' => ['width'=>'150px'],
                'value' => function (Business $model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'idCategories',
                'label' => 'Категория предприятий',
                'value' => function (Business $model) {
                    return $model->categoryNames($model->idCategories);
                },
                'options' => ['width'=>'350px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => BusinessCategory::getCategoryList(),
                    'attribute' => 'idCategories',
                    'options' => [
                        'placeholder' => 'Выберите категорию ...',
                        'id' => 'idCategories',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'label' => 'Статус',
                'format' => 'html',
                'value' => function (Business $model) {
                    return $model->getStatusPaid();
                },
                'filter'  => Select2::widget([
                    'model' => $searchModel,
                    'data' => Business::$statusType,
                    'attribute' => 'status_paid',
                    'options' => [
                        'placeholder' => 'Выберите статус ...',
                        'id' => 'statusId',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'dateCreate',
                'label' => 'Дата оплаты',
                'value' => function(Business $model){
                    if ($model->due_date){
                        $date = new DateTime($model->due_date);
                        return $date->format('Y-m-d');
                    } else {
                        $date = new DateTime();
                        return $date->format('Y-m-d');
                    }
                },
//                'value' => function(Business $model) {
//                    $dateEnd = '';
//                    $transaction = Invoice::find()
//                        ->where([
//                            'user_id' => Yii::$app->user->id,
//                            'object_type' => File::TYPE_BUSINESS,
//                            'object_id' => $model->id,
//                        ])->orderBy(['paid_to' => SORT_DESC])->one();
//                    if ($transaction) {
//                        $dateEnd = $transaction->paid_to;
//                    }
//
//                    return $dateEnd;
//                },
                'options' => ['width' => '120px'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}',
            ],
        ],
    ]); ?>

</div>