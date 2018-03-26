<?php
/* @var $model \common\models\Afisha*/
use common\models\Afisha;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

$start = new DateTime($model->dateStart);

$end = new DateTime($model->dateEnd);
$alias = $model->id . '-' . $model->url;
?>
<div class="sobitie predpr-sobitie">
    <a href="<?= Url::to(['afisha/view', 'alias' => $alias]) ?>" class="sobitie-img">
        <?php if($model->image):?>
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 165) ?>" alt="<?= $model->title?>" />
        <?php endif;?>
    </a>
    <div class="characters">
        <a href="<?= Url::to(['afisha/view', 'alias' => $alias]) ?>" class="title"><?= $model->title?></a>
        <?php if (Yii::$app->user->identity):?>
            <?php if ($model->companys[0]->idUser == Yii::$app->user->identity->id || Yii::$app->user->identity->role == User::ROLE_EDITOR):?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/afisha/update', 'alias' => $model->id . '-' . $model->url]), [])?>
            <?php endif;?>
        <?php endif;?>
        <div class="text"><?= $model->description?></div>
        <div class="raspisanie"><?= Yii::t('afisha', 'When_and_how_much?')?></div>
        <div class="mesto">
            <div class="seans"><?= Yii::t('afisha', 'Time_spending')?> <span><?= $model->getTimes($model->times)?></span></div>
            <div class="year"><?= Yii::t('afisha', 'The_date_of_the')?>
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
            <div class="price"><?= Yii::t('afisha', 'Ticket_price')?> <span><?= $model->price?></span></div>
        </div>
    </div>
</div>