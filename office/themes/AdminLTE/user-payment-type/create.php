<?php
/**
 * @var $this yii\web\View
 * @var $model \common\models\UserPaymentType
 */

$this->title = 'Создание способа оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>