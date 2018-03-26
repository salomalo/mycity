<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AdsColor */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Список цветов', 'url' => ['/ads-color/index', 'adsId' => $model->idAds]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-color-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['/ads-color/index', 'adsId' => $model->idAds], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'isShowOnBusiness',
            'image',
//            'idAds',
        ],
    ]) ?>

</div>
