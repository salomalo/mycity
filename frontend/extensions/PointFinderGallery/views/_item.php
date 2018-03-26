<?php
/**
 * @var $this \yii\web\View
 * @var $item array
 */

use yii\helpers\Html;
?>

<div class="item">
    <div class="item-main">
        <div class="item-image"><?= Html::a(Html::img($item['image']['src']), $item['url']) ?></div>

        <div class="item-labels">
            <?php if (!empty($item['image']['bottom'])) : ?>
                <div class="item-bottom-line">
                    <?php if (!empty($item['image']['bottom']['left'])) : ?>
                        <span class="left"><?= $item['image']['bottom']['left'] ?></span>
                    <?php endif; ?>

                    <?php if (!empty($item['image']['bottom']['right'])) : ?>
                        <span class="right"><?= $item['image']['bottom']['right'] ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <div class="item-detail">
        <div class="item-detail-line item-title">
            <?= Html::a($item['title'], $item['url'], ['class' => 'item-link']) ?>
        </div>

        <?php if (!empty($item['detail'])) : ?>
            <?php if (!empty($item['detail']['second'])) : ?>
                <div class="item-detail-line"><?= $item['detail']['second'] ?></div>
            <?php endif; ?>

            <?php if (!empty($item['detail']['third'])) : ?>
                <div class="item-detail-line"><?= $item['detail']['third'] ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>