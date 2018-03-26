<?php
/**
 * @var $models \common\models\Post[]
 */
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="block-title otstyp-block-title"><?=$title?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content popular-news">
    <?php foreach ($models as $key => $item) : ?>
        <?php
            $date = new DateTime($item->dateCreate);
        ?>
        <?php if ($key == 0) : ?>
            <a href="<?=Url::to($item->getRoute())?>" class="pop-news">
                <div class="pop-news-img">
                    <?php if ($item->image) : ?>
                        <img src="<?= \Yii::$app->files->getUrl($item, 'image', 220) ?>" alt="" />
                    <?php endif; ?>
                </div>
                <div class="pop-news-text">
                    <span class="pop-news-title"><?= $item->title ?></span>
                    <span class="pop-news-date"><?= $date->format('d.m.Y') ?></span>
                </div>
            </a>   
        <?php else : ?>
            <a href="<?=Url::to($item->getRoute())?>" class="pop-news">
                <div class="pop-news-img">
                    <?php if ($item->image) : ?>
                        <img src="<?= \Yii::$app->files->getUrl($item, 'image', 65) ?>" alt="" />
                    <?php endif; ?>
                </div>
                <div class="pop-news-text">
                    <span class="pop-news-title"><?= $item->title ?></span>
                    <span class="pop-news-date"><?= $date->format('d.m.Y') ?></span>
                    <span class="pop-news-comments"><?= $item->getComments($item->id) ?></span>
                </div>
            </a>  
        <?php endif;?>
    <?php endforeach;?>
    <i class="fa fa-long-arrow-right"></i> <a href="<?= Url::to(['post/']) ?>" class="all-news">
        <?= !empty(Yii::$app->params['SUBDOMAINTITLE']) ?
            Yii::t('post', 'All_post_city_{cityTitle}', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']])
            :  Yii::t('post', 'All_post')
        ?></a>
</div>