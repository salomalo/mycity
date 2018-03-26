<?php 
use yii\helpers\Url;
?>
<h2 class="title-list"><?= $this->context->title ?> <?= $this->context->city ?>:</h2>
<div class="block-content">
    <div class="block-title-mini"></div>
        <div class="left-menu price">
            <ul>
            <?php foreach ($models as $model): ?>
            <li>                
                <a href="<?= $this->context->createUrl($model['url']); ?>">
                    <span class="icon-menu"><img src="img/icons/avto.png" alt="" /></span><?= $model['title'] ?>
                </a>
                </li>
            <?php endforeach; ?>
           </ul>
           <a href="<?=Url::to("product/index")?>" class="add-akcia"> <span class="add-akcia-pointer"></span><?= Yii::t('product', 'Show_All_Categories')?></a>
        </div> 
</div>