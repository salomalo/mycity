<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Comment
 * @var $comments \common\models\Comment[]
 * @var $mongo boolean
 */
?>

<!-- Comment -->
<div class="single-box">
    <h2 class="">Коментарии</h2>
    <div class="comment-list">
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <div class="avartar">
                        <img src="/img/avatar.png" alt="Avatar">
                    </div>
                    <div class="comment-body">
                        <div class="comment-meta">
                            <span class="author"><?= $comment->user->username ?></span>
                            <span class="date"><?= $comment->dateCreate ?></span>
                        </div>
                        <div class="comment">
                            <?= $comment->text ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?= $this->render('form', [
    'model' => $model,
    'id' => $id,
    'type' => $type,
    'mongo' => $mongo])
?>

