<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\QuestionConversation */

$this->title = 'Create Question Conversation';
$this->params['breadcrumbs'][] = ['label' => 'Question Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-conversation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

