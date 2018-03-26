<?php
use yii\helpers\Html;

$timeZone = new DateTimeZone(Yii::$app->params['timezone']);
$now = new DateTime("now", $timeZone);
$dateEnd = date('Y-m-d', strtotime($this->context->date));
?>

<div class="rasp">
    <?php if ($schedule = $model->schedule($time)) : ?>
        <div class="listing-detail-section" id="listing-detail-section-seanse">
            <h2 class="page-header"><?= Yii::t('afisha', 'The_schedule_of_sessions_on') . " {$this->context->date}" ?></h2>

            <div class="listing-detail-description-wrapper">
                <?php foreach ($schedule as $item): ?>
                    <?php $alias = "{$item->company->id}-{$item->company->url}" ?>
                    <?php if (($item->dateStart <= $date['schedule']) and ($item->dateEnd >= $dateEnd)): ?>
                        <div class="kinoteatr">
                            <?php if ($item->company->image): ?>
                                <?= Html::a(Html::img(Yii::$app->files->getUrl($item->company, 'image', 65), ['alt' => $item->company->title]), ['business/view', 'alias' => $alias], ['class' => 'title-img']) ?>
                            <?php endif; ?>

                            <div class="other">
                                <?= Html::a("Кинотеатр {$item->company->title}", ['business/view', 'alias' => $alias]) ?>
                                <?php if (!empty($item->times) and is_array($item->times)) : ?>
                                    <div class="time-seans">
                                        <?= Yii::t('afisha', 'Session_Time') ?>
                                        <?php $next = false; ?>
                                        <?php foreach ($item->times as $times): ?>
                                            <?php
                                            $checkTime = ($times > 0) ? $times : '23:59';
                                            $class = '';
                                            $formatTime = strpos($checkTime, '.') ?
                                                $now->format('H.i') : $now->format('H:i');
                                            if ((strtotime($checkTime) <= strtotime($formatTime))) {
                                                $class = 'class="traversed"';
                                            } else {
                                                if (!$next)
                                                    $class = 'class="next"';
                                                $next = true;
                                            }
                                            ?>
                                            <span <?= $class ?>><?= $times ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item->times2D) and is_array($item->times2D)) : ?>
                                    <div class="time-seans">
                                        <?= Yii::t('afisha', 'Times_2D') ?>:
                                        <?php $next = false; ?>
                                        <?php foreach ($item->times2D as $times): ?>
                                            <?php
                                            $checkTime = ($times > 0) ? $times : '23:59';
                                            $class = '';
                                            $formatTime = strpos($checkTime, '.') ?
                                                $now->format('H.i') : $now->format('H:i');
                                            if ((strtotime($checkTime) <= strtotime($formatTime))) {
                                                $class = 'class="traversed"';
                                            } else {
                                                if (!$next) {
                                                    $class = 'class="next"';
                                                }
                                                $next = true;
                                            }
                                            ?>
                                            <span <?= $class ?>><?= $times ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item->times3D) and is_array($item->times3D)) : ?>
                                    <div class="time-seans">
                                        <?= Yii::t('afisha', 'Times_3D') ?>:
                                        <?php $next = false; ?>
                                        <?php foreach ($item->times3D as $times): ?>
                                            <?php
                                            $checkTime = ($times > 0) ? $times : '23:59';
                                            $class = '';
                                            $formatTime = strpos($checkTime, '.') ?
                                                $now->format('H.i') : $now->format('H:i');
                                            if ((strtotime($checkTime) <= strtotime($formatTime))) {
                                                $class = 'class="traversed"';
                                            } else {
                                                if (!$next)
                                                    $class = 'class="next"';
                                                $next = true;
                                            }
                                            ?>
                                            <span <?= $class ?>><?= $times ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="price">
                                    <?= Yii::t('afisha', 'Ticket_price') ?>
                                    <span><?= $item->price ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>