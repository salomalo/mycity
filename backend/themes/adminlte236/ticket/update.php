<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = 'Тикет: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <?= $this->render('_form', [
        'model' => $model,
        'history' => $history,
    ]) ?>

</div>
