<?php
/**
 * @var $models \common\models\Post[]
 */
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="big-title">Другие новости <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content related-news">
    <?php foreach ($models as $item) : ?>
    
    <?php
        $date = new DateTime($item->dateCreate);
    ?>
        <div class="related-new">
            <a href="<?=Url::to($item->getRoute())?>" class="related-news-img">
                <?php if ($item->image) : ?>
                    <img src="<?= \Yii::$app->files->getUrl($item, 'image', 65) ?>" alt="" />
                <?php endif; ?>
            </a>
            <a href="<?=Url::to($item->getRoute())?>" class="related-news-title"><?= $item->title ?></a>
            <div class="related-news-text">
                <?= strip_tags($item->shortText) ?>
            </div>
            <span class="date"><?= $date->format('d.m.Y') ?></span>
            <span class="comm"><?= $item->getComments($item->id) ?></span>
        </div>
    <?php endforeach;?>
    <div class="all-related-news">
        <i class="fa fa-long-arrow-right"></i>
        <a href="<?=\Yii::$app->urlManager->createUrl(['post/index'])?>">
            <?= !empty(Yii::$app->params['SUBDOMAINTITLE']) ?
                Yii::t('post', 'All_post_city_{cityTitle}', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']])
                : Yii::t('post', 'All_post')
            ?>
        </a>
    </div>
</div>
