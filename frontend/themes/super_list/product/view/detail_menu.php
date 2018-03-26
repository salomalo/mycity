<?php
/**
 * @var $this \yii\web\View
 * @var $isCf bool
 */

use yii\helpers\Html;

$req = Yii::$app->request;
?>

<div class="listing-detail-menu-wrapper">
    <div class="listing-detail-menu affix-top">
        <div class="container">
            <ul class="nav nav-pills">
                <ul class="nav nav-pills">
                    <li class="listing-detail-section-description">
                        <a href="#listing-detail-section-description"><?= Yii::t('business', 'Detailed_Description') ?></a></li>

                    <li class="listing-detail-section-contact">
                        <a href="#listing-detail-section-technik-desc"><?= Yii::t('product', 'Detailed_Specifications') ?></a></li>

                    <li class="listing-detail-section-location">
                        <a href="#listing-detail-section-photo"><?= Yii::t('product', 'Gallery') ?></a></li>

                    <li class="listing-detail-section-reviews">
                        <a href="#listing-detail-section-related"><?= Yii::t('product', 'Similar_products_from_the_category') ?> </a></li>

                    <li class="listing-detail-section-goods">
                        <a href="#listing-detail-section-reviews"><?= Yii::t('widgets', 'Comments') ?></a></li>
                </ul>
            </ul>
        </div>
    </div>
</div>