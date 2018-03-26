<div class="big-title rel-tovars"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content  related-afisha related-predpr">
    
    <?php foreach ($models as $key => $item): ?>
    
        <?php if(($key % 2) == 0):?>
            <div class="rel-afisha-block">
        <?php endif;?>
            <div class="rel-afisha">
                <a href="<?=\Yii::$app->urlManager->createUrl(['product/view', 'alias' => $item->url.''])?>" class="title-img">
                    <?php if ($item->image): ?>
                        <img src="<?= \Yii::$app->files->getUrl($item, 'image', 100) ?>" alt="" />
                    <?php endif; ?>
                </a>
                <div class="rel-afisha-other">
                    <p><a href="<?=\Yii::$app->urlManager->createUrl(['product/view', 'alias' => $item->url.''])?>" class="title"><?= $item->title ?></a></p>
                    <p><a href="<?=\Yii::$app->urlManager->createUrl(['product/index', 'pid' => $item->category->url.''])?>" class="cat"><?= $item->category->title ?></a></p>
                    <div class="predl"><?= Yii::t('product', 'Offers')?> <span><?= $item->getCountAds($item->_id); ?></span></div>
                    <div class="comm"><?= $item->getComments($item->_id)?></div>
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
