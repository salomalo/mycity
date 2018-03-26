<?php
/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\LiqpayPayment
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use common\components\LiqPay\LiqPayActions;
use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\LiqPayStatuses;
use common\models\LiqpayPayment;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;

$this->title = Yii::t('business_custom_field', 'Liqpay Payments');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="liqpay-payment-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (LiqpayPayment $model) {
                    return LiqPayStatuses::getConstantLabel($model->status);
                },
                'options' => ['width' => '300px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => LiqPayStatuses::getConstantsLabels(),
                    'attribute' => 'status',
                    'options' => ['placeholder' => 'Статус'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'order_id',
            [
                'attribute' => 'action',
                'value' => function (LiqpayPayment $model) {
                    return LiqPayActions::getConstantLabel($model->action);
                },
                'options' => ['width' => '300px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => LiqPayActions::getConstantsLabels(),
                    'attribute' => 'action',
                    'options' => ['placeholder' => 'Действие'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'amount',
            [
                'attribute' => 'currency',
                'value' => function (LiqpayPayment $model) {
                    return LiqPayCurrency::getConstantLabel($model->currency);
                },
                'options' => ['width' => '200px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => LiqPayCurrency::getConstantsLabels(),
                    'attribute' => 'currency',
                    'options' => ['placeholder' => 'Действие'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'label' => 'Телефон',
                'value' => function (LiqpayPayment $model) {
                    if ($model->data) {
                        $data = Json::decode(base64_decode($model->data), false);
                        return empty($data->sender_phone) ? '' : $data->sender_phone;
                    }

                    return '';
                },
                'options' => ['width' => '200px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => LiqPayCurrency::getConstantsLabels(),
                    'attribute' => 'currency',
                    'options' => ['placeholder' => 'Действие'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>