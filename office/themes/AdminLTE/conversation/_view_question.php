<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Question $question
 */
$isOwner = $question->isOwner(Yii::$app->user->id);
?>

<div class="direct-chat-msg <?= $isOwner ? 'right' : '' ?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left"><?= $question->user->username ?></span>
        <span class="direct-chat-timestamp pull-right"><?= $question->created_at ?></span>
    </div>

    <img class="direct-chat-img" src="/img/<?= $isOwner ? 'avatar5.png' : 'avatar04.png' ?>" alt="Avatar">
    <div class="direct-chat-text" style="word-wrap: break-word;"><?= $question->text ?></div>
</div>

