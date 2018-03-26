<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ActionCategory */

$this->title = 'Create Action Category';
$this->params['breadcrumbs'][] = ['label' => 'Action Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
