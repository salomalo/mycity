<?php
/**
 * @var $this yii\web\View
 * @var $searchModel common\models\search\Invoice
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use common\models\File;
use common\models\Invoice;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\grid\ActionColumn;

$this->title = Yii::t('business_custom_field', 'Invoice');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transactions-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'description',
                'label' => 'Описание',
            ],
            [
                'attribute' => 'amount',
                'label' => 'Сумма',
            ],
            [
                'attribute' => 'paid_status',
                'label' => 'Статус',
                'format' => 'html',
                'value' => function (Invoice $model) {
                    if ($model->paid_status) {
                        return '<div class="business-status-paid">' . Invoice::$statusPaid[$model->paid_status] . '<div>';
                    } else {
                        return '<div class="business-status-overdue">' . Invoice::$statusPaid[$model->paid_status] . '<div>';
                    }
                },
                'filter'  => Select2::widget([
                    'model' => $searchModel,
                    'data' => Invoice::$statusPaid,
                    'attribute' => 'paid_status',
                    'options' => [
                        'placeholder' => 'Выберите статус ...',
                        'id' => 'statusId',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Дата создания',
            ],
            [
                'attribute' => 'paid_from',
                'label' => 'Дата оплаты',
            ],
//            [
//                'attribute' => 'user_id',
//                'value' => function (Invoice $model) {
//                    return $model->user ? $model->user->username : null;
//                },
//            ],
//            [
//                'attribute' => 'object_type',
//                'value' => function (Invoice $model) {
//                    return isset(File::$typeLabels[$model->object_type]) ? File::$typeLabels[$model->object_type] : $model->object_type;
//                },
//            ],
//            [
//                'attribute' => 'object_id',
//                'format' => 'html',
//                'value' => function (Invoice $model) {
//                    return $model->objectLabel;
//                },
//            ],
//            'paid_from:datetime',
//            'paid_to:datetime',
//            'created_at:datetime',

            [
                'class' => ActionColumn::className(),
                'headerOptions' => ['width' => '30'],
                'template' => '{view}',
            ],
        ],
    ]) ?>
</div>