<?php

use kartik\widgets\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */
/* @var $errors_msg array */

$this->title = 'Update Schedule Kino: ' . ' ' . $model->afisha->title;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="schedule-kino-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
