<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AfishaCategory */

$this->title = 'Редактирование категории '. (($isFilm)? 'кино' : 'афиши')  . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Afisha Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="afisha-category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'isFilm' => $isFilm,
    ]) ?>

</div>
