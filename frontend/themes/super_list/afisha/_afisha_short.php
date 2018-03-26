<?php
/**
 * @var bool $showCat
 * @var common\models\Afisha $model
 * @var string $time
 * @var int $cat
 * @var \common\models\Business $company
 */

use common\models\User;
use yii\helpers\Html;

mb_internal_encoding('UTF-8');
$timeZone = new DateTimeZone(Yii::$app->params['timezone']);
$start = new DateTime($model->dateStart, $timeZone);
$end = new DateTime($model->dateEnd, $timeZone);
$alias = "{$model->id}-{$model->url}";
$user = Yii::$app->user->identity;

if (isset($company)){
    $idUser = $company ? $company->idUser : null;
} else {
    if ($model->idsCompany and isset($model->idsCompany[0]) and isset($business[$model->idsCompany[0]])) {
        $company = $business[$model->idsCompany[0]];
        $idUser = $company ? $company->idUser : null;
    } else {
        $idUser = null;
    }

}

$image = Html::img(Yii::$app->files->getUrl($model, 'image', 600), ['class' => 'action-img', 'alt' => $model->title]);
?>

<div class="listing-container">

    <?php if (!defined('CATEGORYID') and $showCat) : ?>
        <h3 class="afisha-cat"><?= $model->category->title ?></h3>
    <?php endif; ?>

    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, ['/afisha/view', 'alias' => $alias, 'time' => $time]) ?>
        </div>

        <div class="listing-row-body border-left-afisha">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/afisha/view', 'alias' => $alias, 'time' => $time], ['class' => 'my-color-link']) ?>
            </h2>
            <div class="listing-row-content">
                <div class="sobitie">
                    <div class="characters">
                        <?php if ($user && ($idUser === $user->id || $user->role === User::ROLE_EDITOR)) : ?>
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/afisha/update', 'alias' => $alias]) ?>
                        <?php endif; ?>
                        <div class="text">
                            <?= mb_substr(strip_tags($model->description), 0, 200) . '...' ?>
                            <p>
                                <?= Html::a(Yii::t('business', 'Read more'), ['/afisha/view', 'alias' => $alias, 'time' => $time], ['class' => 'title']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>