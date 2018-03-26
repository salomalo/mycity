<?php
/**
 * @var $this \yii\web\View
 * @var $modelsVideo \common\models\Post[]
 * @var $modelsPhoto \common\models\Post[]
 * @var $titlePhoto string
 * @var $titleVideo string
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if ($modelsVideo) : ?>
    <div class="block">
        <div class="block-title otstyp-block-title"><?= $titleVideo; ?> <i class="fa fa-long-arrow-down"></i></div>
        <div class="block-content foto-video">
            <div id="amazingslider-wrapper-1" style="display:block;position:relative;max-width:220px;margin:35px auto 0px;">
                <div id="amazingslider-1" style="display:block;position:relative;margin:0 auto;">
                    <ul class="amazingslider-slides" style="display:none;">
                        <?php foreach ($modelsVideo as $item): ?>
                        <?php $date = new DateTime($item->dateCreate); ?>
                        <?php $date = "<span class='slider-foto-video-date'>".$date->format('d.m.Y').'</span>' ?>
                        <?php $count = "<span class='slider-foto-video-comments'>".$item->comment_count.'</span>' ?>
                            <?php foreach ($item->video as $img) : ?>
                                <li>
                                    <img src="http://img.youtube.com/vi/<?= $img ?>/1.jpg"
                                        alt="<a class='slider-foto-video-title' href='<?=Url::to($item->getRoute())?>'>
                                    <?= $this->context->titleCrop($item->title) ?></a>" data-description="<?= $date ?> <?= $count ?>" />
                                    <video preload="none" src="http://www.youtube.com/embed/<?= $img ?>" ></video>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="foto-video-preview-block">
                        <ul class="amazingslider-thumbnails" style="display:none;">
                            <?php foreach ($modelsVideo as $item) : ?>
                                <?php foreach ($item->video as $img) : ?>
                                    <li><?= Html::img("http://img.youtube.com/vi/{$img}/1.jpg") ?></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($modelsPhoto) : ?>
    <div class="block">
        <div class="block-title otstyp-block-title"><?= $titlePhoto; ?> <i class="fa fa-long-arrow-down"></i></div>
        <div class="block-content foto-video">
            <div id="amazingslider-wrapper-2" style="display:block;position:relative;max-width:220px;margin:35px auto 0px;">
                <div id="amazingslider-2" style="display:block;position:relative;margin:0 auto;">
                    <ul class="amazingslider-slides" style="display:none;">
                        <?php foreach ($modelsPhoto as $item): ?>
                            <?php $date = new DateTime($item->dateCreate); ?>
                            <?php $date = "<span class='slider-foto-video-date'>".$date->format('d.m.Y').'</span>' ?>
                            <?php $count = "<span class='slider-foto-video-comments'>".$item->comment_count.'</span>' ?>
                                <li><?= Html::a(Html::img(Yii::$app->files->getUrl($item, 'image', 140), [
                                    'data-description' => $date . ' ' . $count,
                                    'alt' => Html::a($this->context->titleCrop($item->title), Url::to($item->getRoute()), [
                                        'class' => 'slider-foto-video-title',
                                    ])
                                ]), Url::to($item->getRoute())); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="foto-video-preview-block">
                        <ul class="amazingslider-thumbnails" style="display:none;">
                            <?php foreach ($modelsPhoto as $item) : ?>
                                <li><?= Html::img(Yii::$app->files->getUrl($item, 'image', 65)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>