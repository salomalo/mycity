<?php
/**
 * @var $this yii\web\View
 * @var $model \common\models\UserPaymentType
 */

$this->title = 'Изменение способа оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>