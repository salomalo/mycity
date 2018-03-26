<?php
/**
 * @var $modelsSchedule \common\models\ScheduleKino[]
 * @var $models \common\models\Afisha[]
 * @var $city
 */
use yii\helpers\Html;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

?>
<h1 class="title-list"><?= $title ?></h1>
<ul class="index-poster">

    <?php foreach ($models as $item) : ?>

        <?php $alias = "{$item->id}-{$item->url}"; ?>
        <?php $title = (mb_strlen($item->title, 'utf-8') > 22) ? mb_substr($item->title, 0, 22, 'utf-8') . '...' : $item->title; ?>

        <li>
            <?php if ($item->image) : ?>
                <?= Html::a(Html::img(Yii::$app->files->getUrl($item, 'image', 165), ['alt' => $item->title]), ['afisha/view', 'alias' => $alias]) ?>
            <?php endif ?>

            <?= Html::a($title, Url::to(['afisha/view', 'alias' => $alias])) ?>

            <span class="comm"><?= $item->getComments($item->id) ?></span>
            <span class="cat">
                <?= Html::a($item->category->title, ['afisha/index', 'pid' => $item->category->url]) ?>
            </span>
        </li>

    <?php endforeach; ?>
</ul>

<div class="all-news">
    <?php if (empty(Yii::$app->params['SUBDOMAINTITLE'])) : ?>
        <i class="fa fa-long-arrow-right"></i>  <?=Html::a(Yii::t('afisha', 'All afish'), Url::to(['afisha/index']))?>
    <?php endif ?>
</div>