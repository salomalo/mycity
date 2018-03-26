<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */

$this->title = 'Update Schedule Kino: ' . ' ' . $model->afisha->title;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="schedule-kino-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
