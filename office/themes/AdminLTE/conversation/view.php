<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Question[] $questions
 * @var int $conversation_id
 * @var \common\models\QuestionConversation $conversation
 */
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Вопросы</h3>
                <?= Html::a($conversation->typeLabel, ["{$conversation->alias}/view", 'id' => $conversation->object_id], ['class' => 'btn bg-maroon margin']) ?>
            </div>
            <div class="box-body">
                <div class="direct-chat-messages" style="height: auto; max-height: 650px;">

                    <?php foreach ($questions as $question) : ?>
                        <?= $this->render('_view_question', ['question' => $question]) ?>
                    <?php endforeach; ?>
                    
                </div>
            </div>
            <div class="box-footer">

                <?= Html::beginForm(['conversation/new-question']) ?>
                    <div class="input-group">
                        <?= Html::input('text', 'message', null, ['class' => 'form-control', 'placeholder' => 'Введите вопрос']) ?>
                        <?= Html::hiddenInput('conversation_id', $conversation_id) ?>

                        <span class="input-group-btn">
                            <?= Html::button('Отправить', ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) ?>
                        </span>
                    </div>
                <?= Html::endForm() ?>

            </div>
        </div>
    </div>
</div>
