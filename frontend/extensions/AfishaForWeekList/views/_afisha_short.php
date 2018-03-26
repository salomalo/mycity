<?php
/**
 * @var bool $showCat
 * @var common\models\Afisha $model
 * @var bool $showDate показывать ли дату
 * @var string $curDate дата рассматириваемой афиши
 */
use common\models\Afisha;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use common\models\User;
use yii\helpers\Html;

$timeZone = new DateTimeZone(Yii::$app->params['timezone']);
$start = new DateTime($model->dateStart, $timeZone);
$end = new DateTime($model->dateEnd, $timeZone);
$alias = $model->id . '-' . $model->url;
?>

<?php if (!defined('CATEGORYID') and $showCat): ?>
    <h3 class="afisha-cat"><?= $model->category->title ?>:</h3>
<?php endif; ?>

<div class="sobitie">
    <a href="<?= Url::to(['view', 'alias' => $alias]) ?>" class="sobitie-img">
        <?php if ($model->image): ?>
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 165) ?>" alt="<?= $model->title ?>"/>
        <?php endif; ?>
    </a>
    <div class="characters">
        <a href="<?= Url::to(['view', 'alias' => $alias]) ?>" class="title"><?= $model->title ?></a>
        <?php if (Yii::$app->user->identity and ($model->companys[0]->idUser == Yii::$app->user->identity->id or Yii::$app->user->identity->role == User::ROLE_EDITOR)): ?>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/afisha/update', 'alias' => $model->id . '-' . $model->url]), []) ?>
        <?php endif ?>
        <div class="text"><?= $model->description ?></div>
        <div class="raspisanie" style="clear: both;"><?= Yii::t('afisha', 'When_and_where') ?></div>
        <div class="mesto">
            <?php foreach ($model->companys as $item): ?>
                <a href="<?= Url::to(['business/view', 'alias' => $item->id . '-' . $item->url]) ?>"><?= $item->title ?></a>
            <?php endforeach; ?>
            <?php if ($model->price): ?>
                <span class="price"><?= Yii::t('afisha', 'Ticket_price') ?> <?= $model->price ?></span>
            <?php endif; ?>

            <div class="seans">
                <?php if ($times = $model->getTimes($model->times)): ?>
                    <?= Yii::t('afisha', 'Time_spending') ?> <span><?= $times ?></span>
                <?php endif; ?>
            </div>

            <div class="year"><?= Yii::t('afisha', 'The_date_of_the') ?>
                <span>
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
            </div>
        </div>
    </div>
</div>
