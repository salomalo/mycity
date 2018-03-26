<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Afisha */

$this->title = ($isFilm)? 'Добавить фильм' : 'Create Afisha';
$this->params['breadcrumbs'][] = ['label' => 'Afishas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-create">

    <?= $this->render(($isFilm)? '_form_film' : '_form', [
        'model' => $model,
        'checkCompany' => $checkCompany,
        'dislpayCheck' => false,
    ]) ?>

</div>
