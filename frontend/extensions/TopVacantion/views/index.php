<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="block-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content popular-vacancy">
    <?php foreach ($models as $item):?>
       <?php if(isset($item->company)): ?>
        <div class="vac">
            <?php if($item->company->image):?>
                <img src="<?= \Yii::$app->files->getUrl($item->company, 'image', 65) ?>" alt="" />
            <?php endif;?>
            <a href="<?= Url::to(['vacantion/view', 'alias' => $item->id . '-' . $item->url])?>" class="title"><?= $item->title?></a>
            <?php if($item->category):?>
            <a href="<?= Url::to(['vacantion/index', 'pid' => $item->category->url])?>" class="cat"><?= $item->category->title?></a>
            <?php endif; ?>
            <div class="pred" style="width: 115px;"><?= Yii::t('vacantion', 'Where')?>
                <a href="<?= Url::to(['business/view', 'alias' => $item->company->id . '-' . $item->company->url]) ?>">
                    <?= $item->company->title?>
                </a></div>
        </div>
        <?php endif; ?>
    <?php endforeach;?>
 
    <i class="fa fa-long-arrow-right"></i> <a href="<?= Url::to(['vacantion/index']) ?>" class="top-100-link"><?= Yii::t('vacantion', 'All_jobs')?> <?=(defined('SUBDOMAINTITLE'))? SUBDOMAINTITLE : ''?></a>
</div>