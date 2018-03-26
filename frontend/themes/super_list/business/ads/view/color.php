<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
?>

<div class="listing-detail-section" id="listing-detail-section-video">
    <h2 class="page-header">Цвет товара</h2>
    <div class="listing-detail-description-wrapper">
        <div style="background-color: <?= $model->color ?>; width: 30px; height: 30px"></div>
    </div>
</div>