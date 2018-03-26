<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>

<div class="big-title"><?= $title?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content related-afisha related-predpr">
    <?php foreach ($models as $key => $item): ?>
    
        <?php $alias = "{$item->id}-{$item->url}";?>
    
        <?php if(($key % 2) == 0):?>
            <div class="rel-afisha-block">
        <?php endif;?>
        <div class="rel-afisha">
            <a href="<?= Url::to(['view', 'alias' => $alias]) ?>" class="title-img">
                <?php if ($item->image): ?>
                    <img src="<?= Yii::$app->files->getUrl($item, 'image', 65) ?>" alt="<?= $item->title ?>" />
                <?php endif; ?>
            </a>
            <div class="rel-afisha-other">
                <p><a href="<?= Url::to(['view', 'alias' => $alias]) ?>" class="title"><?= $item->title ?></a></p>
                <p>
                    <?php foreach ($item->categoryList($item->idCategories) as $cat):?>
                        <a href="<?= Url::to(['business/index', 'pid' => $cat['url']]) ?>" class="cat"><?= $cat['title'] ?></a>
                    <?php endforeach;?>
                </p>
                <div class="dinamic">+120</div>
                <div class="comm"><?= $item->getComments($item->id)?></div>
            </div>
        </div>
        <?php if(($key % 2) == 1) : ?>
            </div>
        <?php endif;?>
    
    <?php endforeach; ?>
    
    <?php if((count($models) % 2) == 1):?>
        <div class="rel-afisha"></div>
        </div>
    <?php endif;?>
</div>
