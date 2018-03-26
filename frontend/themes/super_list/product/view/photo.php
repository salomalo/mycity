<?php
use common\extensions\Gallery\Gallery;
use common\models\File;
use yii\helpers\Html;

?>

<div class="listing-detail-section" id="listing-detail-section-photo">
    <h2 class="page-header"><?= Yii::t('product', 'Gallery') ?></h2>
    <div class="listing-detail-attributes">
        <div class="tov-gallery">

            <?php if ($model->gallery): ?>
                <?php foreach ($model->gallery as $gal): ?>

                    <?php
                    echo Html::a(
                        Html::img(
                            \Yii::$app->files->getUrlGallery($model, 'gallery', 500, $gal),
                            [
                                'width' => 310
                            ]
                        ),
                        \Yii::$app->files->getUrlGallery($model, 'gallery', null, $gal),
                        [
                            'class' => 'fancybox',
                            'data-fancybox-group' => 'gallery2',
                            'title' => ''
                        ]
                    );
                    ?>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
    <?php
    //echo Gallery::widget([
    //    'model' => $model,
    //    'type' => File::TYPE_PRODUCT_GALLERY,
    //    'mongo' => true,
    //    ]);
    ?>
</div>

