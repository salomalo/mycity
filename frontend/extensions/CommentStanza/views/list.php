<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Comment
 * @var $comments \common\models\Comment[]
 * @var $mongo boolean
 */
?>

<div role="tabpanel" class="tab-pane" id="reviews">
    <?php foreach ($comments as $comment): ?>
        <div class="reviewBox clearfix">
            <div class="reviewFrame">
                <i class="fa fa-user"></i>
            </div><!-- ( REVIEW FRAME END ) -->
            <div class="reviewContent">
                <div class="reviewTitle"><?= $comment->user->username ?></div>
                <div class="stars text-left">
                    <?php
                    $date = new DateTime($comment->dateCreate);
                    echo $date->format('Y-m-d H:i:s');
                    ?>
                </div><!-- ( STARS END ) -->
                <p><?= $comment->text ?></p>
            </div><!-- ( REVIEW CONTENT END ) -->
        </div><!-- ( REVIEW BOX END ) -->
    <?php endforeach; ?>

    <div class="stripe-1">
        <?= $this->render('form', [
            'model' => $model,
            'id' => $id,
            'type' => $type,
            'mongo' => $mongo])
        ?>
    </div>
</div>



