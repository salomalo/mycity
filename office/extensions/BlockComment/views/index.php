<?php
/**
 * @var $this yii\base\View
 * @var $countComment integer
 */
?>
<?php if ($countComment > 0) : ?>
    <a href="<?= \yii\helpers\Url::to(['/comment/review']) ?>" class="link-widget">
<?php endif; ?>
    <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>

        <div class="info-box-content">
            <span class="info-box-text"><?= Yii::t('app', 'Comments') ?></span>
            <span class="info-box-number"><?= $countComment ?></span>
        </div>
    </div>
<?php if ($countComment > 0) : ?>
    </a>
<?php endif; ?>
