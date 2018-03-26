<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ParseKino */

$this->title = 'Update Parse Kino: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parse Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parse-kino-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
