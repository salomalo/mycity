<?php
use common\extensions\Gallery\Gallery;
use common\models\File;
use yii\helpers\Html;

?>
<?php if (Gallery::widget(['model' => $model, 'mongo' => true, 'type' => File::TYPE_GALLERY])) : ?>
    <div class="listing-detail-section" id="listing-detail-section-photo">
        <h2 class="page-header"><?= Yii::t('ads', 'Photo') ?></h2>
        <div class="listing-detail-description-wrapper">
            <?= Gallery::widget([
                'model' => $model,
                'mongo' => true,
                'type' => File::TYPE_GALLERY,
            ]) ?>
        </div>
    </div>
<?php endif; ?>