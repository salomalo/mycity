<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Lang */

$this->title = 'Update Lang: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Langs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lang-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
