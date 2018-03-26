<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = 'Тикет: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="ticket-update">


    <?= $this->render('_answerform', [
        'model' => $model,
        'history' => $history,
    ]) ?>

</div>
