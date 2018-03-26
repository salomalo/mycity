<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\KinoGenre */

$this->title = 'Create Kino Genre';
$this->params['breadcrumbs'][] = ['label' => 'Kino Genres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kino-genre-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
