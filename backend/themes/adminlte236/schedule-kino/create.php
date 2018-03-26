<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */

$this->title = 'Добавить расписание к фильму';
$this->params['breadcrumbs'][] = ['label' => 'Schedule Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-kino-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
