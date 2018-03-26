<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="big-title"><?= $title?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content related-akcii">
    <?php foreach ($models as $item): ?>
    
    <?php 
        $start = new DateTime($item->dateStart);
        $end = new DateTime($item->dateEnd);
        $alias = $item->id . '-' . $item->url;
    ?>
        <div class="related-akcia">
            <a href="<?= Url::to(['action/view', 'alias' => $alias])?>" class="related-akcia-img">
                <?php if ($item->image): ?>
                    <img src="<?= \Yii::$app->files->getUrl($item, 'image', 195) ?>" alt="<?=$item->title?>" />
                <?php endif; ?>
            </a>
            <div class="related-akcia-characters">
                <a href="<?= Url::to(['action/view', 'alias' => $alias])?>" class="related-akcia-title"><?=$item->title?></a>
                <a href="<?= Url::to(['action/index', 'pid' => $item->category->url])?>" class="related-akcia-cat"><?=$item->category->title?></a>
                <div class="related-akcia-mesto">
                        <?= Yii::t('action', 'Where_is_the')?> 
                    <a href="<?= Url::to(['business/view', 'alias' => $item->companyName->id . '-' . $item->companyName->url])?>">
                        <?=$item->companyName->title?>
                    </a>
                </div>
                <div class="related-akcia-time"><?= Yii::t('action', 'The_offer_is_valid')?> <span>с <?= $start->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></span></div>
            </div>
        </div>
    <?php endforeach;?>
</div>
    <?php

//echo \yii\helpers\BaseVarDumper::dump($models, 10, true); 
