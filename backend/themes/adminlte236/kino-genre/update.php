<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KinoGenre */

$this->title = 'Update Kino Genre: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Kino Genres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kino-genre-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
