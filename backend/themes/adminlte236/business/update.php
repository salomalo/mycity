<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

$this->title = Yii::t('business', 'Update_busi') . ': ' . Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('business', 'Btn_update_a');
?>
<div class="business-update">

    <?= $message; ?>
    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'selectCity' => $selectCity,
    ]) ?>

</div>
