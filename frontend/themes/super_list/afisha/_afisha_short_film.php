<?php
/**
 * @var int $showCatFilm
 * @var common\models\Afisha $model ;
 * @var string $time
 */

use yii\helpers\Html;

mb_internal_encoding('UTF-8');
$timeZone = new DateTimeZone(Yii::$app->params['timezone']);
$now = new DateTime('now', $timeZone);
$alias = "{$model->id}-{$model->url}";
$image = Html::img(Yii::$app->files->getUrl($model, 'image', 600), ['class' => 'action-img', 'alt' => $model->title]);
?>

<div class="listing-container">

    <?php if (!defined('CATEGORYID') and $showCatFilm === 0) : ?>
        <h3 class="afisha-cat"><?= Yii::t('afisha', 'Cinema') ?></h3>
    <?php endif; ?>

    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, ['afisha/view', 'alias' => $alias, 'time' => $time]) ?>
        </div>

        <div class="listing-row-body border-left-afisha">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['afisha/view', 'alias' => $alias, 'time' => $time], ['class' => 'my-color-link']) ?>
            </h2>
            <div class="listing-row-content">
                <div class="sobitie">
                    <div class="characters">
                        <p style="text-align: justify;"><?= mb_substr(strip_tags($model->description), 0, 100) . '...' ?></p>
                        <p>
                            <?= Html::a(Yii::t('business', 'Read more'), ['afisha/view', 'alias' => $alias, 'time' => $time], ['class' => 'title']) ?>
                        </p>

                        <?php if ($schedule = $model->schedule($date)) : ?>
                            <div class="raspisanie"><?= Yii::t('afisha', 'The_schedule_of_sessions_on')?> <?= $date?>:</div>
                            <?php foreach ($schedule as $item) : ?>
                                <?php if ($item->company) : ?>
                                    <div class="mesto">
                                        <?= Html::a("Кинотеатр {$item->company->title}", ['/business/view', 'alias' => "{$item->company->id}-{$item->company->url}"]) ?>

                                        <?php if (!empty($item->times) and is_array($item->times)) : ?>
                                            <div class="seans"><?= Yii::t('afisha', 'Session_Time')?>
                                                <?php $next = false; ?>
                                                <?php foreach ($item->times as $time) : ?>
                                                    <?php
                                                    $checkTime = ($time > 0) ? $time : '23:59';
                                                    $class = '';
                                                    $formatTime = (strpos($checkTime, '.')) ? $now->format('H.i') : $now->format('H:i');

                                                    if ($checkTime <= $formatTime) {
                                                        $class = ($date == $now->format('Y-m-d'))? 'class="traversed"' : '';
                                                    } else {
                                                        if (!$next) {
                                                            $class = ($date == $now->format('Y-m-d'))? 'class="next"' : '';
                                                        }
                                                        $next = true;
                                                    }
                                                    ?>
                                                    <span <?= $class ?>><?= $time ?></span>
                                                <?php endforeach;?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($item->times2D) and is_array($item->times2D)) : ?>
                                            <div class="seans"><?= Yii::t('afisha', 'Times_2D')?>:
                                                <?php $next = false; ?>
                                                <?php foreach ($item->times2D as $time) : ?>
                                                    <?php
                                                    $checkTime = ($time > 0) ? $time : '23:59';
                                                    $class = '';
                                                    $formatTime = (strpos($checkTime, '.')) ? $now->format('H.i') : $now->format('H:i');

                                                    if ($checkTime <= $formatTime) {
                                                        $class = ($date == $now->format('Y-m-d'))? 'class="traversed"' : '';
                                                    } else {
                                                        if (!$next) {
                                                            $class = ($date == $now->format('Y-m-d'))? 'class="next"' : '';
                                                        }
                                                        $next = true;
                                                    }
                                                    ?>
                                                    <span <?= $class ?>><?= $time ?></span>
                                                <?php endforeach;?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($item->times3D) and is_array($item->times3D)) : ?>
                                            <div class="seans"><?= Yii::t('afisha', 'Times_3D')?>:
                                                <?php $next = false; ?>
                                                <?php foreach ($item->times3D as $time) : ?>
                                                    <?php
                                                    $checkTime = ($time > 0) ? $time : '23:59';
                                                    $class = '';
                                                    $formatTime = (strpos($checkTime, '.')) ? $now->format('H.i') : $now->format('H:i');

                                                    if ($checkTime <= $formatTime) {
                                                        $class = ($date == $now->format('Y-m-d'))? 'class="traversed"' : '';
                                                    } else {
                                                        if (!$next) {
                                                            $class = ($date == $now->format('Y-m-d'))? 'class="next"' : '';
                                                        }
                                                        $next = true;
                                                    }
                                                    ?>
                                                    <span <?= $class ?>><?= $time ?></span>
                                                <?php endforeach;?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>

        <div class="listing-row-properties">
            <dl>
                <dt><span class="genre"><strong><?= Yii::t('afisha', 'Genre') ?></strong></span></dt>
                <dd><span><?= $model->genreNames($model->genre) ?></span></dd>

                <dt><span class="country"><strong><?= Yii::t('afisha', 'Country') ?></strong></span></dt>
                <dd><span><?= $model->country ?></span></dd>
            </dl>
        </div>
    </div>
</div>