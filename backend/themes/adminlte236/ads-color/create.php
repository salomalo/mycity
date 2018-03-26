<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsColor */
/* @var $adsId string */

$this->title = 'Добавить цвет';
$this->params['breadcrumbs'][] = ['label' => 'Список цветов', 'url' => ['/ads-color/index', 'adsId' => $adsId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-color-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
