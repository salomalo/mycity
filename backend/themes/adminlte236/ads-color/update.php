<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdsColor */

$this->title = 'Редактирование : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Список цветов', 'url' => ['/ads-color/index', 'adsId' => $model->idAds]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="ads-color-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
