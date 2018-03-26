<?php

use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

?>

<div class="big-title"><?= $title?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content  related-afisha related-predpr">
    <?php foreach ($models as $key => $item): ?>
        <?php if ($item->company):?>
            <?php $alias = $item->company->id . '-' . $item->company->url?>
            <?php if(($key % 2) == 0):?>
                <div class="rel-afisha-block">
            <?php endif;?>
                    <div class="rel-afisha">
                        <a href="<?= Url::to(['business/view', 'alias' => $alias])?>" class="title-img">
                            <?php if ($item->company->image): ?>
                                <img src="<?= \Yii::$app->files->getUrl($item->company, 'image', 65) ?>" alt="" />
                            <?php endif; ?>
                        </a>
                        <div class="rel-afisha-other">
                            <p><a href="<?= Url::to(['vacantion/view', 'alias' => $item->id . '-' . $item->url])?>" class="title"><?= $item->title ?></a></p>
                            <p><a href="<?= Url::to(['vacantion/index', 'pid' => $item->category->url])?>" class="cat"><?= $item->category->title ?></a></p>
                            <div class="map"><?= Yii::t('vacantion', 'Where')?>
                                <a href="<?= Url::to(['business/view', 'alias' => $alias])?>"><?= $item->company->title?></a>
                            </div>
                        </div>
                    </div>
            <?php if(($key % 2) == 1):?>
                </div>
            <?php endif;?>
        <?php endif;?>
    <?php endforeach;?>
    
    <?php if((count($models) % 2) == 1):?>
            <div class="rel-afisha">
            </div>
        </div>
    <?php endif;?>
</div>