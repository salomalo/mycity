<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $enabled bool[]
 */

use yii\helpers\Html;

$req = Yii::$app->request;
$enabled = empty($enabled) ? [] : $enabled;
$alias = "{$model->id}-{$model->url}";
?>

<div class="listing-detail-menu-wrapper">
    <div class="listing-detail-menu affix-top">
        <div class="container">
            <ul class="nav nav-pills">
                <?php if (!empty($enabled['detail'])) : ?>
                    <li class="listing-detail-section-description">
                        <?= Html::a(Yii::t('business', 'Detailed_Description'), ['/business/view', 'alias' => $alias]) ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['description'])) : ?>
                    <li class="listing-detail-section-description">
                        <?= Html::a(Yii::t('business', 'Detailed_Description'), '#listing-detail-section-description') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['categories'])) : ?>
                    <li class="listing-detail-section-property-amenities">
                        <?= Html::a(Yii::t('business', 'Product categories'), '#listing-detail-section-property-amenities') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['contact'])) : ?>
                    <li class="listing-detail-section-contact">
                        <?= Html::a(Yii::t('business', 'Contacts'), '#listing-detail-section-contact') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['attributes'])) : ?>
                    <li class="listing-detail-section-attributes">
                        <?= Html::a(Yii::t('business', 'at_length'), '#listing-detail-section-attributes') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['video'])) : ?>
                    <li class="listing-detail-section-video">
                        <?= Html::a('Видео', '#listing-detail-section-video') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['location'])) : ?>
                    <li class="listing-detail-section-location">
                        <?= Html::a(Yii::t('business', 'map'), '#listing-detail-section-location') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['comments'])) : ?>
                    <li class="listing-detail-section-reviews">
                        <?= Html::a(Yii::t('widgets', 'Comments'), '#listing-detail-section-reviews') ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['goods'])) : ?>
                    <li class="listing-detail-section-goods">
                        <?= Html::a(Yii::t('business', 'Goods'), ['/business/goods', 'alias' => $alias]) ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['action'])) : ?>
                    <li class="listing-detail-section-action">
                        <?= Html::a(Yii::t('business', 'Promotions'), ['/business/action', 'alias' => $alias]) ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($enabled['afisha'])) : ?>
                    <li class="listing-detail-section-afisha">
                        <?= Html::a(Yii::t('business', 'Poster'), ['/business/afisha', 'alias' => $alias]) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>