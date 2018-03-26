<?php
/* @var $model \common\models\Afisha */
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
?>

<?php
$now = date('Y-m-d');
?>
<?php
$alias = $model->id . '-' . $model->url;
$future = false;
?>

<div class="sobitie predpr-sobitie">
    <a href="<?=Url::to(['afisha/view','alias' => $alias])?>" class="sobitie-img">
        <?php if ($model->image) : ?>
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 165) ?>" alt="<?=$model->title ?>" />
        <?php endif;?>
    </a>
    <div class="characters">
        <a href="<?=Url::to(['afisha/view','alias' => $alias])?>" class="title"><?=$model->title ?></a>
        <div class="genre">
            <?= Yii::t('afisha', 'Genre')?> <span><?= $model->genreNames($model->genre) ?></span>
        </div>
        <span class="year"><?= Yii::t('afisha', 'Graduation_Year')?> <span><?= $model->year ?></span></span>
        <span class="country"><?= Yii::t('afisha', 'Country')?> <span><?= $model->country ?></span></span>
        <div class="text"><?= $model->description ?></div>
        <?php if ($schedule = $model->scheduleByBusiness($now, $this->context->id)) : ?>

            <?php foreach ($schedule as $item) : ?>
                <div class="raspisanie"><?= Yii::t('afisha', 'The_schedule_of_sessions_on') ?>
                    <?php
                    if (date($item->dateStart) < $now) {
                        echo date('d.m.Y', strtotime($item->dateStart)), '- ', date('d.m.Y', strtotime($item->dateEnd));
                    } else {
                        echo date('d.m.Y', strtotime($item->dateStart));
                        $future = true;
                    }
                    ?>:
                </div>
                <div class="mesto">
                    <span class="price"><?= Yii::t('afisha', 'Ticket_price')?> <?= $item->price ?></span>
                    <?php if (!empty($item->times) and is_array($item->times)) : ?>
                        <div class="seans"><?= Yii::t('afisha', 'Session_Time')?>
                            <?php $next = false; ?>
                            <?php foreach ($item->times as $time) : ?>
                                <?php
                                $checkTime = ($time > 0) ? $time : '23:59';
                                $class = '';
                                $formatTime = (strpos($checkTime, '.')) ? date('H.i') : date('H:i');

                                if ((strtotime($checkTime) <= strtotime($formatTime)) && !$future) {
                                    $class = 'class="traversed"';
                                } else {
                                    if (!$next && !$future) {
                                        $class = 'class="next"';
                                    }
                                    $next = true;
                                }
                                ?>
                                <span <?= $class ?> ><?= $time ?></span>
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
                                $formatTime = (strpos($checkTime, '.')) ? date('H.i') : date('H:i');

                                if ((strtotime($checkTime) <= strtotime($formatTime)) && !$future) {
                                    $class = 'class="traversed"';
                                } else {
                                    if (!$next && !$future) {
                                        $class = 'class="next"';
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
                                $formatTime = (strpos($checkTime, '.')) ? date('H.i') : date('H:i');

                                if ((strtotime($checkTime) <= strtotime($formatTime)) && !$future) {
                                    $class = 'class="traversed"';
                                } else {
                                    if (!$next && !$future) {
                                        $class = 'class="next"';
                                    }
                                    $next = true;
                                }
                                ?>
                                <span <?= $class ?>><?= $time ?></span>
                            <?php endforeach;?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
        <?php endif; ?>
    </div>
</div>