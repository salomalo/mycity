<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use yii\helpers\Html;

?>

<div class="listing-detail-section" id="listing-detail-section-description">
    <h2 class="page-header"><?= Yii::t('business', 'Detailed_Description') ?></h2>

    <div class="listing-detail-description-wrapper">
        <p><?= $model->description ?></p>
    </div>
</div>