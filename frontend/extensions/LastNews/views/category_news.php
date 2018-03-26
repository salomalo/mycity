<?php
use yii\helpers\Url;
?>
<div class="big-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="related-news">
    <div class="news-img-block">
        <?php foreach ($models as $key => $value): ?>
        
        <?php 
            $date = new DateTime($value->dateCreate);
        ?>
            <?php if($key < 2): ?>
                <div class="news-img">
                    <a href="<?=Url::to($value->getRoute())?>" class="news-im">
                        <?php if ($value->image): ?>
                            <img src="<?= \Yii::$app->files->getUrl($value, 'image', 220) ?>" alt="" />
                        <?php endif; ?>
                    </a>
                    <a href="<?=Url::to($value->getRoute())?>" class="title"><?= $value->title ?></a>
                    <span class="date"><?= $date->format('d.m.Y, H:i') ?></span>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    
    <?php if(count($models) > 2):?>
        <div class="news-list-block">
            <ul>
                <?php foreach ($models as $key => $value): ?>

                <?php 
                    $date = new DateTime($value->dateCreate);
                ?>
                    <?php if($key >= 2): ?>
                        <li>
                            <a href="<?=Url::to($value->getRoute())?>"><?= $value->title ?></a>
                            <span class="date"><?= $date->format('d.m.Y, H:i') ?></span>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    
    <div class="all-news">
        <i class="fa fa-long-arrow-right"></i> <a href="<?=\Yii::$app->urlManager->createUrl(['post/index', 'pid' => $aliasCategory])?>">
                    <?= Yii::t('post', 'All_{categoryCity}', ['categoryCity' => $title])?></a>
    </div>
</div>