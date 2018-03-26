<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = 'Создать Вопрос';
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
