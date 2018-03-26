<?php 
use yii\helpers\Url;
?>
<div class="index-title"><?=$title?> <?=$titleCity?>: </div>
<div class="block-content popular-predpr">
    <?php foreach ($models as $item): ?>
    <a href="<?= \Yii::$app->urlManager->createUrl(['business/view', 'alias' => $item->url]) ?>" class="pop-predpr" title="<?= $item->title ?>">
        <?php if ($item->image): ?>
            <img src="<?= \Yii::$app->files->getUrl($item, 'image', 65) ?>" alt="" />
        <?php else: ?>
            <img src="demo/pop_predpriztie.jpg" alt="" />
        <?php endif; ?>
    </a>
    <?php endforeach;?>
</div>
<div class="all-news">
    <i class="fa fa-long-arrow-right"></i> <a href="<?=Url::to("business/index")?>" class="top-100-link"><?= Yii::t('business', 'All_firm_{cityTitle}', ['cityTitle' => ($idCity)? $titleCity : ''])?> </a>
</div>