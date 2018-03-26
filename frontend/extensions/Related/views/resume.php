<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<div class="big-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content  related-afisha related-predpr">
    <?php foreach ($models as $key => $item): ?>
    
        <?php $alias = $item->id . '-' . $item->url ;?>
        <?php if(($key % 2) == 0):?>
            <div class="rel-afisha-block">
        <?php endif;?>
                <div class="rel-afisha">
                    <a href="<?= Url::to(['resume/view', 'alias' => $alias])?>" class="title-img">
                        <?php if ($item->photoUrl): ?>
                            <img src="<?= \Yii::$app->files->getUrl($item, 'photoUrl', 65) ?>" alt="" />
                        <?php endif; ?>
                    </a>
                    <div class="rel-afisha-other">
                        <p><a href="<?= Url::to(['resume/view', 'alias' => $alias])?>" class="title"><?= $item->title ?></a></p>
                        <p><a href="<?= Url::to(['resume/index', 'pid' => $item->category->url])?>" class="cat"><?= $item->category->title ?></a></p>
                        <div class="terms"><?= Yii::t('resume', 'Looking_for_a_job')?> <span><?= ($item->isFullDay)? Yii::t('vacantion', 'Full_day') : Yii::t('vacantion', 'Not_a_full_day') ?>, 
            <?= ($item->isOffice)? Yii::t('vacantion', 'office_work') : Yii::t('vacantion', 'work_remotely') ?></span></div>
                        <span class="experience"><?= Yii::t('resume', 'Experience')?> <span><?= $item->experience ?></span></span>
                        <span class="money"><?= Yii::t('resume', 'Salary')?> <span><?= $item->salary ?></span></span>
                    </div>
                </div>
        <?php if(($key % 2) == 1):?>
            </div>
        <?php endif;?>
    
    <?php endforeach;?>
    
    <?php if((count($models) % 2) == 1):?>
            <div class="rel-afisha">
            </div>
        </div>
    <?php endif;?>
</div>