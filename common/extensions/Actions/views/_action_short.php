<?php 

use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\Html;
use common\models\User;

    $remainingTime = $model->getRemainingTime($model->dateStart, $model->dateEnd); 
    $alias = $model->id . '-' . $model->url;
?>

    <div class="akcia">
        <a href="<?= Url::to(['action/view', 'alias' => $alias]) ?>" class="akcia-mini-img">
            <?php if ($model->image): ?>
                <img src="<?= \Yii::$app->files->getUrl($model, 'image') ?>" alt="<?= $model->title ?>" />
    <?php endif; ?>
        </a>
        <div class="akcia-mini-characters">
            <a href="<?= Url::to(['action/view', 'alias' => $alias]) ?>" class="title"><?= $model->title ?></a>
            <?php if (Yii::$app->user->identity):?>
                <?php if ($model->companyName->idUser == Yii::$app->user->identity->id || Yii::$app->user->identity->role == User::ROLE_EDITOR):?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/action/update', 'alias' => $model->id . '-' . $model->url]), [])?>
                <?php endif;?>
            <?php endif;?>

            <div class="time">  
                <span><?= Yii::t('action', 'The_offer_is_valid') ?></span>
                <span class="period">
                    <?php if($remainingTime['start']->format('d.m.Y') != $remainingTime['end']->format('d.m.Y')):?>
                        с <?= $remainingTime['start']->format('d.m.Y') ?> по <?= $remainingTime['end']->format('d.m.Y') ?>
                    <?php else:?>
                        <?= $remainingTime['start']->format('d.m.Y') ?>
                    <?php endif;?>
                </span>
                <span class="ostatok <?= $remainingTime['style'] ?>">
                    <?php if (!$remainingTime['interval']->invert): ?>
                        <img src="img/icons/bomb.png" alt="" />
                        <?= (!empty($remainingTime['left'])) ? $remainingTime['left'] : '' ?>
    <?php endif; ?>
                </span>
            </div>

            <div class="mesto_money">  
                <?php if ($model->price): ?>
                    <span class="money"><?= Yii::t('action', 'cost') ?> <?= $model->price ?></span>
    <?php endif; ?>
            </div>

            <div class="view-comm-cat">
                <span class="view"><?= ($model->countView) ? $model->countView->count : 0 ?></span>
                <span class="comm"><?= $model->getComments($model->id) ?></span>

            </div>
        </div>
    </div>