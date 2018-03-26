    <?php
/**@var $model \common\models\Product*/
use yii\helpers\Url;
?>
<div class="index-title"><?= $title ?>  <?=$this->context->city?></div>
<div class="block-content  related-afisha related-predpr">
    <div class="rel-afisha-block">
    <?php foreach ($models as $item): ?>
    <div class="rel-afisha">
        <a href="<?= Url::to(['product/view','alias'=>$item->url.''])?>" class="title-img">
            <?php if($item->image):?>
                <img src="<?= \Yii::$app->files->getUrl($item, 'image') ?>" width="65" heigth="65" alt="" />
            <?php endif;?>
        </a>        
            <div class="rel-afisha-other">
                <p><a href="<?= Url::to(['product/view','alias'=>$item->url.''])?>" class="title"><?= $item->title?></a></p>
                <p><a href="<?= Url::to(['product/index','pid'=>$item->category->url.''])?>" class="cat"><?= $item->category->title?></a></p>
                <div class="predl"><?= Yii::t('product', 'Offers')?> <span><?= $item->getCountAds($item->_id); ?></span></div>
                <div class="comm"><?= $item->getComments($item->_id)?></div>
            </div>
    </div>    
   <?php endforeach;?>
</div>
</div>
<div class="all-news">
    → <a href="<?= Yii::$app->urlManager->createUrl('product/index')?>"><?= Yii::t('product', 'All_goods')?> <?=$this->context->city?></a>
</div>