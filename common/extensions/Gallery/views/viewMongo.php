<?php

/**
 * @var $gallery File[]
 */

use common\models\Gallery;
use common\models\File;
use common\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div id="gallery" class="ad-gallery">
    <div class="ad-image-wrapper">
    </div>
    <div class="ad-controls">
    </div>
    <div class="ad-nav">
        <div class="ad-thumbs">
            <ul class="ad-thumb-list">  

                <?php foreach ($gallery as $img): ?>
                    <li>
                        <?php $echoImg = Yii::$app->files->getUrl($gal, 'attachments', 100, $img->name, $this->context->id . '/' . $img->pid); ?>
                        <?php $fullImg = Yii::$app->files->getUrl($gal, 'attachments', 500, $img->name, $this->context->id . '/' . $img->pid); ?>
                        <?= Html::a(Html::img($echoImg, ['title' => '', 'alt' => '']), $fullImg) ?>
                    </li>
                <?php endforeach; ?>

                <?php if ($model->images) : ?>
                    <?php foreach ($model->images as $img) : ?>
                        <li>
                            <?php $echoImg = Yii::$app->files->getUrl($model, 'images', 100, $img); ?>
                            <?php $fullImg = Yii::$app->files->getUrl($model, 'images', 500, $img); ?>
                            <?= Html::a(Html::img($echoImg, ['title' => '', 'alt' => '']), $fullImg) ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>

