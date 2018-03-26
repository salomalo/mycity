<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */

$this->title = 'Create Work Vacantion';
$this->params['breadcrumbs'][] = ['label' => 'Work Vacantions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-vacantion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
