<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="big-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content related-afisha">
    <?php foreach ($models as $key => $item): ?>
        
        <?php $alias = $item->id . '-' .$item->url;?>
    
        <?php if(($key % 2) == 0):?>
            <div class="rel-afisha-block">
        <?php endif;?>
                <div class="rel-afisha">
                    <a href="<?= Url::to(['afisha/view', 'alias' => $alias])?>" class="title-img">
                        <?php if ($item->image): ?>
                            <img src="<?= \Yii::$app->files->getUrl($item, 'image', 70) ?>" alt="<?= $item->title ?>" />
                        <?php endif; ?>
                    </a>
                    <div class="rel-afisha-other">
                        <p><a href="<?= Url::to(['afisha/view', 'alias' => $alias])?>" class="title"><?= $item->title ?></a></p>
                        <p><a href="<?= Url::to(['afisha/index', 'pid' => $item->category->url])?>" class="cat"><?= $item->category->title?></a></p>
                        <div class="ganr"><?= Yii::t('afisha', 'Genre')?> <span><?= $item->genreNames($item->genre) ?></span></div>
                        <div class="country"><?= Yii::t('afisha', 'Country')?> <span><?= $item->country ?></span></div>
                        <div class="comm"><?= $item->getComments($item->id)?></div>
                    </div>
                </div>
        <?php if(($key % 2) == 1):?>
            </div>
        <?php endif;?>
    
    <?php endforeach; ?>
    
    <?php if((count($models) % 2) == 1):?>
            <div class="rel-afisha">
            </div>
        </div>
    <?php endif;?>
</div>