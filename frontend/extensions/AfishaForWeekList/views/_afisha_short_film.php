<?php
/**
 * @var int $showCatFilm
 * @var common\models\Afisha $model;
 * @var bool $showDate
 * @var string $curDate
 */
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

$alias = "{$model->id}-{$model->url}";
?>

<?php if ($showDate) : ?>
    <h3 class="afisha-date"><?= $curDate ?></h3>
<?php endif; ?>

<?php if (!defined('CATEGORYID') and $showCatFilm === 0) : ?>
    <h3 class="afisha-cat"><?= Yii::t('afisha', 'Cinema')?></h3>
<?php endif; ?>

<div class="sobitie">
    <a href="<?=Url::to(['view','alias' => $alias])?>" class="sobitie-img">
        <?php if($model->image):?>
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 165) ?>" alt="<?=$model->title ?>" />
        <?php endif;?>
    </a>
    <div class="characters">
        <a href="<?=Url::to(['view','alias' => $alias])?>" class="title"><?=$model->title ?></a>
        <div class="genre">
            <?= Yii::t('afisha', 'Genre') ?> <span><?= $model->genreNames($model->genre) ?></span>
        </div>
        <span class="year"><?= Yii::t('afisha', 'Graduation_Year')?> <span><?= $model->year ?></span></span>
        <span class="country"><?= Yii::t('afisha', 'Country')?> <span><?= $model->country ?></span></span>
        <div class="text"><?= $model->description ?></div>

    </div>
</div>