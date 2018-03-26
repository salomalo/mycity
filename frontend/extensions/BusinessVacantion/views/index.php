<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>
<?php foreach ($models as $item): ?>

<?php $alias = $model->id . '-' . $model->url;?>

<div class="predpr pred-vac">
    <a href="<?=Url::to(['business/view', 'alias' => $alias, 'tab'=>'vacantion', 'pidTab' => $item->id])?>" class="title-img">
        <?php if($item->company->image):?>
            <img src="<?= \Yii::$app->files->getUrl($item->company, 'image', 165) ?>" alt="" />
        <?php endif;?>
    </a>
    <div class="characters">
        <a href="<?=Url::to(['business/view', 'alias' => $alias, 'tab'=>'vacantion', 'pidTab' => $item->id])?>" class="title"><?=$item->title ?></a>
        <div class="money"><?= Yii::t('vacantion', 'We_offer_salary')?> <span><?= $item->salary ?></span></div>
        <div class="terms"><?= Yii::t('vacantion', 'Working_conditions')?> <span><?= ($item->isFullDay)? Yii::t('vacantion', 'Full_day') : Yii::t('vacantion', 'Not_a_full_day') ?>, 
            <?= ($item->isOffice)? Yii::t('vacantion', 'office_work') : Yii::t('vacantion', 'work_remotely') ?></span></div>
        <span class="map">
                <?= Yii::t('vacantion', 'Where')?> 
            <a href="<?=Url::to(['business/view', 'alias' => $item->company->id . '-' .$item->company->url])?>">
                <?= $item->company->title?>
            </a>
        </span>
        <span class="cat"><a href="<?=Url::to(['vacantion/index', 'pid' => $item->category->url])?>"><?= $item->category->title?></a></span>
    </div>
</div>
<?php endforeach; ?>
