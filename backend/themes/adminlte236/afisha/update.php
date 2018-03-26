<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */

$this->title = 'Update ' . (($model->isFilm)? 'Film: ' : 'Afisha: ') . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Afishas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="afisha-update">

    <?= $this->render(($model->isFilm)? '_form_film' : '_form', [
        'model' => $model,
        'checkCompany' => $checkCompany,
        'dislpayCheck' => true,
    ]) ?>

</div>
