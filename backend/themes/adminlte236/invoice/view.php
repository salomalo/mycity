<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Invoice
 */

use common\models\File;
use common\models\User;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$typeLabel = isset(File::$typeLabels[$model->object_type]) ? File::$typeLabels[$model->object_type] : $model->object_type;
?>

<div class="transactions-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'ID пользователя',
                'value' =>  $model->user_id,
            ],
            'user.username',
            [
                'attribute' => 'object_type',
                'value' => $typeLabel,
            ],
            [
                'attribute' => 'object_id',
                'format' => 'html',
                'value' => $model->objectLabel,
            ],
            [
                'label' => 'ID предприятия',
                'value' => $model->object_id,
            ],
            [
                'attribute' => 'amount',
                'label' => 'Сумма',
            ],
            [
                'format' => 'html',
                'label' => 'Статус',
                'value' => $model->getStatusPaid(),
            ],
            'paid_from:datetime',
            'paid_to:datetime',
            'created_at:datetime',
        ],
    ]) ?>
</div>