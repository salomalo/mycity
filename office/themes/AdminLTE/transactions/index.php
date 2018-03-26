<?php
/**
 * @var $this \yii\web\View
 * @var $invoices \common\models\Invoice
 * @var $searchModel common\models\search\Invoice
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use common\models\Invoice;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('transactions', 'Transactions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-index">
    <h4 class="box-title"><?= Yii::t('transactions', 'Invoice_list') ?></h4>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'paid_from',
                'label' => 'Дата',
            ],
            [
                'label' => 'Статус',
                'format' => 'html',
                'value' => function (Invoice $model) {
                    if ($model->paid_status == Invoice::PAID_NO) {
                        return '<span class="label label-danger">Не оплачено</span>';
                    } elseif ($model->paid_status == Invoice::PAID_YES) {
                        return '<span class="label label-success">Оплачено</span>';
                    }
                },
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-shopping-basket"></span>',
                            (new yii\grid\ActionColumn())->createUrl('transactions/view', $model, ['id' => $model->id], 1), [
                                'title' => 'Оплатить',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return '';
                    },
                    'update' => function ($url, $model) {
                        return '';
                    },
                ]
            ],
        ],
    ]); ?>
</div>
