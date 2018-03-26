<?php

use frontend\controllers\CommentController;
?>
<a name="comments"></a>
<div id="comments" data-mongo="<?= ($isMongo) ? '1' : '0' ?>">
    <div class="big-title">
        <?= Yii::t('widgets', 'Comments') ?>
        <i class="fa fa-long-arrow-down"></i>
    </div>
    <div class="block-comments">
        <div class="form-comment-add">
            <?php echo $this->render('form', ['model' => $model, 'id' => $id, 'type' => $type]);?>
        </div>
        <?php foreach ($comments as $item) {
            echo $this->render('comment', ['item' => $item, 'type' => $type, 'nesting' => 0, 'isReply' => false, 'isNew' => false , 'limit' => $limit]);
            CommentController::getChildrenComments($item, $id, $type, $limit);
        } ?>
    </div>
</div>
