<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ViewCount */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = ['label' => 'View Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-count-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
