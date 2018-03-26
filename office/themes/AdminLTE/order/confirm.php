<?php
use yii\helpers\Html;

$this->title = 'Заказ ' . $model->id;
?>

<div class="orders-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

