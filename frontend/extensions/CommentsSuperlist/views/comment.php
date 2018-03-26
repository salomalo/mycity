<?php
/**
 * @var $item \common\models\Comment
 * @var $type
 * @var $nesting
 * @var $limit
 * @var $isReply
 */

$date = new DateTime($item->dateCreate);
?>

<li id="review-<?= $item->id ?>" data-id="<?= $item->id ?>" data-type="<?= $type ?>" data-nesting="<?= $nesting ?>" data-limit="<?= $limit ?>">
    <div class="review clearfix">

        <div class="review-image" data-background-image="/img/avatar.png">
            <img src="/img/avatar.png" alt="">
        </div>

        <div class="review-inner">
            <div class="review-header">
                <h2><?= $item->user ? $item->user->username : '' ?></h2>

                <div class="review-rating-wrapper">
                    <span style="color: black; font-size: 13px">
                        <?= date('H:i d.m.Y', strtotime($item->dateCreate)) ?>
                    </span>
                </div>
            </div>

            <div class="review-content-wrapper">
                <div class="review-content">
                    <div class="comment">
                        <p><?= $item->text ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>