<?php
use yii\helpers\Url;
?>
<h2 class="title-list"><?= $title ?>:</h2>
<div class="last-news">
    <div class="big-news">
        <a class="title" href="<?=Url::to($models[0]->getRoute())?>"><?=$models[0]->title ?></a>
        <a href="<?=Url::to($models[0]->getRoute())?>" class="title-img">
            <?php if ($models[0]->image): ?>
                <img src="<?= \Yii::$app->files->getUrl($models[0], 'image', 350) ?>" alt="" />
            <?php endif; ?>
        </a>
        <div class="text"><?= $models[0]->shortText?></div>
        <?php
            $date = new DateTime($models[0]->dateCreate);
        ?>
        <span class="date"><?= $date->format('d.m.Y, H:i') ?></span>
        <span class="view"><?= ($models[0]->countView)? $models[0]->countView->count : 0 ?></span>
        <span class="comm"><?= $models[0]->getComments($models[0]->id)?></span>
    </div>
    
    <div class="mini-news">
        <?php foreach ($models as $key => $value): ?>

            <?php 
                if($key > 0):
                $date = new DateTime($value->dateCreate);
            ?>
                <div class="news">
                    <a href="<?=Url::to($value->getRoute())?>" class="title-img">
                        <?php if ($value->image): ?>
                            <img src="<?= \Yii::$app->files->getUrl($value, 'image', 90) ?>" alt="" />
                        <?php endif; ?>
                    </a>
                    <a class="title" href="<?=Url::to($value->getRoute())?>"><?=$value->title ?></a>
                    <span class="date"><?= $date->format('d.m.Y, H:i') ?></span>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
</div>