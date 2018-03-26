<?php
/**@var $model \common\models\Product*/
use yii\helpers\Url;
?>
<div class="block-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content popular-news  popular-tovars"> 
    <?php foreach ($models as $item): ?>
        <a href="<?= Url::to(['view','alias'=>$item->url.''])?>" class="pop-news">
            <?php if($item->image):?>
                <img src="<?= \Yii::$app->files->getUrl($item, 'image') ?>" width="65" heigth="65" alt="" />
            <?php endif;?>
            <span class="pop-news-title"><?= $item->title?> <?= $item->model?></span>
            <span class="predlog"><span><?= Yii::t('product', 'Offers')?></span> <?= $item->getCountAds($item->_id); ?></span>
            <span class="pop-news-comments"><?= $item->getComments($item->_id)?></span>
        </a>  
    <?php endforeach;?>
</div>
