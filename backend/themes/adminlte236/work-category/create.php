<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WorkCategory */

$this->title = 'Create Work Category';
$this->params['breadcrumbs'][] = ['label' => 'Work Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
