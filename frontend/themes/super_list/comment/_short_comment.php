<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Comment
 */
use common\models\Comment;

?>

<div id="opinion-<?= $model->id ?>" class="b-reviews__item js-reviews-item" itemprop="review" itemscope="itemscope"
     itemtype="http://schema.org/Review">
    <div class="b-reviews__item-wrap">
        <div class="b-reviews__left-column h-mr-15">
            <div class="b-reviews__author b-text-hider b-text-hider_type_multi-line" itemprop="author">
                <span class="b-text-hider__right-shadow"></span>
                <span class="b-reviews__author-name"><?= $model->user->username ?></span>
            </div>
            <i class="icon-date_small b-reviews__date-icon"></i>
            <time datetime="2017-01-17" class="b-reviews__date" itemprop="dateCreated">
                <?php
                $dateCreate = strtotime($model->dateCreate);
                $date = new DateTime();
                $dateCreate = $date->setTimestamp($dateCreate);
                ?>
                <?= $dateCreate->format('d-m-Y') ?>
            </time>
            <div class="b-reviews__rating" itemprop="reviewRating" itemscope="itemscope" itemtype="http://schema.org/Rating">
                <span class="h-hidden">
                    <span itemprop="bestRating">5</span>
                    <span itemprop="worstRating">1</span>
                    <span itemprop="ratingValue">5</span>
                </span>
                <span class="b-progress b-progress_type_square b-progress_color_green">
                    <span class="b-progress__bg-grey"></span>
                    <span class="b-progress__bar" style="width: <?= $model->rating_business * 20 ?>%"></span>
                    <span class="icon-progress_square b-progress__square"></span>
                </span>&nbsp;
                <span class="b-progress-status-label " itemscope="itemscope" itemtype="http://schema.org/Rating">
                    <span class="h-hidden">
                        <span itemprop="bestRating">5</span>
                        <span itemprop="worstRating">1</span>
                        <span itemprop="ratingValue">5</span>
                    </span><?= Comment::$typesRatingBusiness[$model->rating_business] ?></span>
            </div>
        </div>

        <div class="h-layout-hidden js-opinion-content">
            <div class="b-reviews__holder" itemprop="reviewBody">
                <div class="h-mv-10">
                    <?= $model->text?>
                </div>
                <div class="h-mt-20"></div>
                <?php if ($model->correct_price && $model->correct_price != Comment::RATING_DO_NOT_REMEMBER) : ?>
                    <div class="h-mv-10">
                        <span class="b-reviews__icon">
                            <i class="icon-actual-price h-vertical-middle"></i>
                        </span>
                        <i class="b-reviews__caption">
                            Цена
                            <b><?= $model->correct_price == Comment::RATING_YES ? 'актуальна' : 'не актуальна' ?></b>
                        </i>
                    </div>
                <?php endif; ?>

                <?php if ($model->order_executed_on_time && $model->order_executed_on_time != Comment::RATING_DO_NOT_REMEMBER) : ?>
                    <div class="h-mv-10">
                        <span class="b-reviews__icon">
                            <i class="icon-done-in-time h-vertical-middle"></i>
                        </span>
                        <i class="b-reviews__caption">
                            Заказ выполнен
                            <b><?= $model->order_executed_on_time == Comment::RATING_YES ? 'в срок' : 'не в срок' ?></b>
                        </i>
                    </div>
                <?php endif; ?>

                <?php if ($model->order_executed_on_time && $model->order_executed_on_time != Comment::CALLBACK_DEFAULT) : ?>
                    <div class="h-mv-10">
                        <span class="b-reviews__icon">
                            <i class="icon-reply-time h-vertical-middle"></i>
                        </span>
                        <i class="b-reviews__caption">
                            Связались
                            <b><?= Comment::$typesCallback[$model->order_executed_on_time] ?></b>
                        </i>
                    </div>
                <?php endif; ?>

                <?php if ($model->product_availability && $model->product_availability != Comment::RATING_DO_NOT_REMEMBER) : ?>
                    <div class="h-mv-10">
                        <span class="b-reviews__icon">
                            <i class="icon-actual-availability h-vertical-middle"></i>
                        </span>
                        <i class="b-reviews__caption">
                            Товар
                            <b><?= $model->product_availability == Comment::RATING_YES ? 'в наличии' : 'не в наличии' ?></b>
                        </i>
                    </div>
                <?php endif; ?>

                <?php if ($model->correct_description && $model->correct_description != Comment::RATING_DO_NOT_REMEMBER) : ?>
                    <div class="h-mv-10">
                        <span class="b-reviews__icon">
                            <i class="icon-actual-description h-vertical-middle"></i>
                        </span>
                        <i class="b-reviews__caption">
                            Описание товара
                            <b><?= $model->correct_description == Comment::RATING_YES ? 'актуально' : 'не актуально' ?></b>
                        </i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="h-layout-clear"></div>
    </div>
</div>
