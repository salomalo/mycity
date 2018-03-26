<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use yii\helpers\Html;

$remainingTime = $model->getRemainingTime($model->dateStart, $model->dateEnd);
?>

<div class="listing-detail-section" id="listing-detail-section-description">
    <h2 class="page-header"><?= Yii::t('business', 'Detailed_Description') ?></h2>

    <div class="listing-detail-description-wrapper">
        <div class="full-akcia">
            <div class="text">
                <p><?= $model->description ?></p>
            </div>
        </div>
    </div>
</div>