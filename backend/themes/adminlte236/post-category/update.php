<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostCategory */

$this->title = 'Update Post Category: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
