<?php
/**
 * @var $this \yii\base\View
 * @var $model \common\models\Action
 */

$remainingTime = $model->getRemainingTime($model->dateStart, $model->dateEnd);
?>

<div class="listing-detail-section" id="listing-detail-section-action-details">
    <h2 class="page-header"><?= Yii::t('action', 'Details') ?></h2>

    <div class="listing-detail-attributes">
        <ul>
            <li class="price">
                <strong class="key"><?= Yii::t('action', 'The_offer_is_valid') ?></strong>
                <span class="value">
                    <?php if ($remainingTime['start']->format('d.m.Y') != $remainingTime['end']->format('d.m.Y')): ?>
                        с <?= $remainingTime['start']->format('d.m.Y') ?> по <?= $remainingTime['end']->format('d.m.Y') ?>
                    <?php else: ?>
                        <?= $remainingTime['start']->format('d.m.Y') ?>
                    <?php endif; ?>
                </span>
            </li>

            <?php if (!$remainingTime['interval']->invert): ?>
                <li class="listing_shopping_category">
                    <strong class="key"><?= $remainingTime['leftText'] ?></strong>
                    <span class="value"><?= $remainingTime['leftWithOutText'] ?></span>
                </li>
            <?php endif; ?>


            <?php if ($model->tags): ?>
                <li class="listing_color">
                    <strong class="key">Теги</strong>
                    <span class="value"><?= $model->tags ?></span>
                </li>
            <?php endif; ?>

            <?php if (!empty($model->price)): ?>
                <li class="listing_size">
                    <strong class="key"><?= Yii::t('action', 'cost') ?> </strong>
                    <span class="value"><?= $model->price ?></span>
                </li>
            <?php endif; ?>
        </ul>
    </div><!-- /.listing-detail-attributes -->
</div>
