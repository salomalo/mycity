<?php
use common\models\ProductCustomfield;
use console\models\Arenda;
use frontend\extensions\MyStarRating\MyStarRating;
use yii\helpers\Html;
use yii\helpers\Url;

$alias = $model->companyName ? ($model->companyName->id . '-' . $model->companyName->url) : null;
?>

<div class="listing-detail-section" id="listing-detail-section-detail">
    <h2 class="page-header"><?= Yii::t('action', 'Where_is_the') ?></h2>
    <div class="listing-detail-attributes">
        <div class="mesto">
            <?php if ($model->companyName) : ?>
                <div class="mesto-loc">
                    <a href="<?= Url::to(['business/view', 'alias' => $alias]) ?>" class="mesto-logo">
                        <?php if ($model->companyName->image): ?>
                            <img src="<?= \Yii::$app->files->getUrl($model->companyName, 'image', 165) ?>"
                                 alt="<?= $model->companyName->title ?>"/>
                        <?php endif; ?>
                    </a>
                    <div class="mesto-characters">
                        <div class="mesto-title">
                            <span class="diamond"><img src="../img/icons/diamond.png" alt=""/></span>
                            <?= $model->companyName ? Html::a($model->companyName->title, ['business/view', 'alias' => $alias]) : '' ?>
                        </div>
                        <?php if ($model->companyName and !empty($model->companyName->address[0])): ?>
                            <div class="mesto-address"><?= Yii::t('business', 'Address') ?>
                                <span><?= $model->companyName->address[0]['address'] ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($model->companyName->phone)): ?>
                            <div class="mesto-phone">
                                <?= Yii::t('business', 'Phone') ?> <span><?= $model->companyName->phone ?></span>
                            </div>
                        <?php endif; ?>
                        <?= MyStarRating::widget(['id' => $model->companyName->id, 'rating' => $model->companyName->rating, 'readOnly' => true]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>