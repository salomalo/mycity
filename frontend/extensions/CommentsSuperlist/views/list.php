<?php
/**
 * @var $isMongo
 * @var $model
 * @var $type
 * @var $comments
 * @var $limit
 * @var $title
 */

use frontend\controllers\CommentController;
use yii\helpers\Url;

?>

<div id="listing-detail-section-reviews" class="listing-detail-section" data-mongo="<?= $isMongo ? '1' : '0' ?>" data-url="<?= Url::to(['/comment/add-superlist']) ?>">
    <h2 class="page-header review-title"><?= $title ? $title : Yii::t('widgets', 'Comments') ?></h2>

    <ul class="review-list">
        <?php foreach ($comments as $item) : ?>
            <?= $this->render('comment', ['item' => $item, 'type' => $type, 'nesting' => 0, 'isReply' => false, 'isNew' => false , 'limit' => $limit]) ?>
            <?php CommentController::getChildrenComments($item, $id, $type, $limit) ?>
        <?php endforeach; ?>
    </ul>

    <?= $this->render('form', ['model' => $model, 'id' => $id, 'type' => $type]) ?>
</div>