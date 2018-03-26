<?php
/**
 * @var $item \common\models\Comment
 */
use yii\helpers\Html;
use kartik\widgets\StarRating;

$style = '';
if ($isReply) {
    $nesting = $nesting + 1;
    //$style = 'style = "margin-left: ' . ($nesting * 30) . 'px;"';
    $style = '';
}
$date = new DateTime($item->dateCreate);
?>

<div class="comment" data-id="<?= $item->id ?>" data-type="<?= $type ?>" data-nesting="<?= $nesting ?>" data-limit="<?= $limit ?>" >
   
    <div class="comm-other">
        <?php if(isset($item->user->photoUrl) and $item->user->photoUrl):?>
            <img src="<?= \Yii::$app->files->getUrl($item->user, 'photoUrl', 25) ?>" class="avatar" alt="" />
        <?php endif;?>
        
        <a class="user-name"><?= isset($item->user->username) ? $item->user->username : '' ?></a>
        <span class="comm-time"><?= $date->format('d.m.Y , h:i') ?></span>
        <a href="#" class="diez">#</a>
        <?php if($nesting < $limit):?>
            <?php if(Yii::$app->user->identity):?>
                <?= Html::a(Yii::t('widgets', 'Reply'), '', ['class' => 'comment_reply']); ?>
            <?php else: ?>
                <?= Html::a(Yii::t('widgets', 'Reply'), ['javascript:void(0);'], [
                    'class' => 'no-login',
                    'onclick'=>'login("'.\Yii::$app->urlManager->createUrl("site/showmodal-login").'");return false;'
                    ]); ?>
            <?php endif;?>
        <?php endif;?>
        
        <?php if(Yii::$app->user->identity && $item->idUser == Yii::$app->user->identity->id) {
            echo Html::a(Yii::t('widgets', 'Delete'), '', [
                'class' => 'comment_delete',
                'data-url' => \Yii::$app->urlManager->createUrl(['comment/delete']),
                'data-message' => Yii::t('widgets', 'You are sure delete comment')
            ]);
            echo ' ';
            if ((strtotime($item->dateCreate) >= (time() - 5 * 60))) {
                echo Html::a(Yii::t('widgets', 'Update'), '', [
                    'class' => 'comment_update',
                    'data-url' => \Yii::$app->urlManager->createUrl(['comment/update'])
                ]);
            }
        }
        ?>
        
        <div class="rating">
            <div class="div_ratingdown">
                <a id="downRatingButton" href="" data-do="unlike" class="comment_like"></a>
            </div>
            <?php $like = $item->like - $item->unlike; ?>
            <span class="rating-val <?= ($like >= 0) ? '' : 'bad' ?>"><?= $like ?></span>
            <div class="div_ratingup">
                <a id="upRatingButton" href="" data-do="like" class="comment_like"></a>
            </div>
        </div>
    </div>
    
    <div class="comm-text">
        <?= $item->text ?>
    </div>
</div>