<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Comment
 * @var $comments \common\models\Comment[]
 * @var $mongo boolean
 */
?>
<?php foreach ($comments as $comment): ?>
    <div class="comment row">
        <div class="col-sm-3 author">
            <div class="info-author">
                <span><strong><?= $comment->user->username ?></strong></span>
                <em>
                    <?php
                    $date = new DateTime($comment->dateCreate);
                    echo $date->format('Y-m-d');
                    ?>
                </em>
            </div>
        </div>
        <div class="col-sm-9 commnet-dettail">
            <?= $comment->text ?>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->render('form', [
    'model' => $model,
    'id' => $id,
    'type' => $type,
    'mongo' => $mongo])
?>

