<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ViewCount */

$this->title = 'Изменение: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'View Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="view-count-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
