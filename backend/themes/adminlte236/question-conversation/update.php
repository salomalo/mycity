<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\QuestionConversation */

$this->title = 'Update Question Conversation: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Question Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="question-conversation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

