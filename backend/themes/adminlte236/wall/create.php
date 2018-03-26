<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wall */

$this->title = 'Create Wall';
$this->params['breadcrumbs'][] = ['label' => 'Walls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wall-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
