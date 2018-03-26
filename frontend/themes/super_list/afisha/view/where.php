<?php
use common\models\Afisha;
use yii\helpers\Html;

$timeZone = new DateTimeZone(Yii::$app->params['timezone']);
$start = new DateTime($model->dateStart, $timeZone);
$end = new DateTime($model->dateEnd, $timeZone);
?>

<div class="listing-detail-section" id="listing-detail-section-trailler">
    <h2 class="page-header"><?= Yii::t('afisha', 'When_and_where') ?></h2>
    <div class="listing-detail-attributes afisha-place">
        <ul>
            <?php if ($times = $model->getTimes($model->times)) : ?>
                <li class="listing_event_type">
                    <strong class="key"><?= Yii::t('afisha', 'Time_spending') ?></strong>
                    <span class="value"><?= $times ?></span>
                </li>
            <?php endif; ?>

            <li class="listing_event_type">
                <strong class="key"><?= Yii::t('afisha', 'The_date_of_the') ?></strong>
                <span class="value" itemprop="startDate" content="<?= $start->format('Y-m-d') ?>">
                    <?php
                    if ((int)$model->repeat === Afisha::REPEAT_DAY) {
                        echo Afisha::$repeat_type[Afisha::REPEAT_DAY];
                    } elseif ((int)$model->repeat === Afisha::REPEAT_WEEK) {
                        echo Afisha::$repeat_type[Afisha::REPEAT_WEEK];
                        if ($model->afishaWeekRepeat) {
                            echo ' в ', $model->afishaWeekRepeat->stringOfDays;
                        }
                    } elseif ($start->format('d.m.Y') != $end->format('d.m.Y')) {
                        echo 'с ', $start->format('d.m.Y'), ' по ', $end->format('d.m.Y');
                    } else {
                        echo $start->format('d.m.Y');
                    }
                    ?>
                </span>
            </li>

            <?php if ($model->price) : ?>
                <li class="listing_event_type_border">
                    <strong class="key"><?= Yii::t('afisha', 'Ticket_price') ?></strong>
                    <span class="value"><?= $model->price ?></span>
                </li>
            <?php endif; ?>

            <li>
                <strong class="key"><?= Yii::t('afisha', 'Afisha_place') ?></strong>
                <span class="value">
                    <?php foreach ($model->companys as $item) : ?>
                        <?php if (is_object($item)) : ?>
                            <?= Html::a($item->title, ['business/view', 'alias' => "{$item->id}-{$item->url}"]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
            </li>
        </ul>
    </div>
</div>
