<?php
/**
 * @var $modelsSchedule \common\models\ScheduleKino[]
 * @var $models \common\models\Afisha[]
 * @var $city
 */
use yii\helpers\Html;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\VarDumper;

?>
<h1 class="title-list"><?= $title ?> <?= date("Y-m-d") ?></h1>
<ul class="index-poster">
    <?php foreach ($modelsSchedule as $item) : ?>

        <?php $afisha = $item->afisha; ?>
        <?php $alias = "{$afisha->id}-{$afisha->url}"; ?>
        <?php $genres = $afisha->genreNames($afisha->genre); ?>
        <?php $title = (mb_strlen($afisha->title, 'utf-8') > 22) ? mb_substr($afisha->title, 0, 22, 'utf-8') . '...' : $afisha->title; ?>

        <li class="index-kino">
            <?php if ($afisha->image) : ?>
                <?= Html::a(Html::img(Yii::$app->files->getUrl($afisha, 'image', 165), ['alt' => $afisha->title]), ['afisha/view', 'alias' => $alias]) ?>
            <?php endif ?>

            <?= Html::a($title, ['afisha/view', 'alias' => $alias]) ?>
            <?php if ($afisha->isFilm) : ?>
                <div class="genre">
                    <?= Yii::t('afisha', 'Genre') ?>
                    <span>
                        <?= (mb_strlen($genres, 'utf-8') > 20) ? mb_substr($genres, 0, 20, 'utf-8') . '...' : $genres ?>
                    </span>
                </div>
            <?php endif ?>
            <span class="comm"><?= $afisha->getComments($afisha->id) ?></span>
            <span class="cat">
                <?= Html::a($afisha->category->title, ['afisha/index', 'pid' => $afisha->category->url]) ?>
            </span>
        </li>
    <?php endforeach; ?>

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
    <?php if (!empty(Yii::$app->request->city)) : ?>
        <?= Html::a((Yii::t('afisha', 'All_events') . " $city"), ['afisha/index']) ?>
    <?php else : ?>
        <i class="fa fa-long-arrow-right"></i>
        <?= Html::a(Yii::t('afisha', 'All_events'), '', ['data-modalurl' => Url::to(['site/showmodal-message-change-city']), 'class' => 'message_change_city']) ?>
    <?php endif ?>
</div>