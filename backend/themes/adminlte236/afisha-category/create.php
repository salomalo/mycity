<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AfishaCategory */

$this->title = ($isFilm)? 'Добавить категорию кино' : 'Добавить категорию афиши';
$this->params['breadcrumbs'][] = ['label' => 'Afisha Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'isFilm' => $isFilm,
    ]) ?>

</div>
