<?php
/**
 * @var $this \yii\web\View
 * @var $cf array
 */
?>

<div class="listing-detail-section" id="listing-detail-section-attributes">
    <h2 class="page-header"><?= Yii::t('business', 'at_length') ?></h2>

    <div class="listing-detail-attributes">
        <ul>
            <?php foreach ($cf as $title => $value) : ?>
                <li class="cf-item">
                    <strong class="key"><?= $title ?></strong>
                    <span class="value"><?= $value ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>