<?php 
use yii\helpers\Html;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

/**@var \common\models\Action[] $models*/
?>
<h1 class="title-list"><?=$title?>:</h1>
<ul class="index-content-akcia <?= (count($models) < 3)? 'index-content-akcia-amall' : ''?>">
    <?php foreach($models as $item):?>
    <?php
            $remainingTime = $item->getRemainingTime($item->dateStart, $item->dateEnd); 
            $alias = $item->id . '-' . $item->url;
    ?>    
    <li class="akcia">
    <center>
        <a href="<?=Url::to($item->getRoute())?>" class="akcia-mini-img1">
            <?php if($item->image):?>
                <img src="<?= \Yii::$app->files->getUrl($item, 'image', 311) ?>" alt="<?=$item->title?>">
            <?php endif;?>
        </a>
    </center>
        <div class="akcia-mini-characters">
            <div class="akcia-mini-characters_title">
                <a href="<?=Url::to($item->getRoute())?>" class="title">
                    <?= (mb_strlen($item->title, 'utf-8') > 50)?
                        mb_substr($item->title, 0, 50, 'utf-8'). '...' : 
                        $item->title
                    ?>
                </a>
            </div>
            
            <div class="mesto">  
                <?= Yii::t('action', 'Where_is_the')?> 
                <a href="<?= $item->companyName ? Url::to($item->companyName->getRoute()) : '' ?>" class="mesto-loc">
                    <?= isset($item->companyName->title) ? $item->companyName->title : ''?>
                </a>
            </div>
            <div class="time">
                <?php /*
                <span><?= Yii::t('action', 'The_offer_is_valid')?></span><span class="period">до <?= $remainingTime['end']->format('d.m.Y') ?></span>
                */ ?>
                <span class="ostatok ostatok-warning"><img src="img/icons/bomb.png" alt=""><?= $remainingTime['left']?></span>
            </div>
            <div class="view-comm-cat">                                        
                <span class="comm"><?=$item->getComments($item->id)?></span>
                <?php /*
                <span class="cat"><a href="<?=Url::to($item->category->getRoute($item->companyName->city->subdomain))?>"><?=$item->category->title?></a></span>
                */ ?>
            </div>
        </div>    
    </li>
    <?php endforeach ?>
</ul>       
<div class="all-news">
    <?php if (!empty(Yii::$app->params['SUBDOMAINTITLE'])) : ?>
        <i class="fa fa-long-arrow-right"></i>
        <?=Html::a(Yii::t('action', 'All_share_{cityTitle}', ['cityTitle' => $city]), Url::to(['action/index']))?>
    <?php else : ?>
        <i class="fa fa-long-arrow-right"></i>  <?=Html::a(Yii::t('action', 'All_shares'), Url::to(['action/index']))?>
    <?php endif ?>
</div>


