<?php
/**
 * @var $this \yii\web\View
 * @var $elements array
 */
?>
<div class="point-finder-gallery">
    <div class="gallery-title">

    </div>

    <div class="owl-carousel owl-theme">
        <?php foreach ($elements as $item) : ?>
            <?= $this->render('_item', ['item' => $item]) ?>
        <?php endforeach; ?>
    </div>
</div>