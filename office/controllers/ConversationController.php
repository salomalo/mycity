<?php
namespace office\controllers;

use common\models\Business;
use common\models\Question;
use common\models\QuestionConversation;
use console\models\File;
use yii;
use yii\helpers\Url;
use yii\web\HttpException;

class ConversationController extends DefaultController
{
    public function actionIndex($business_id){
        $conversations = QuestionConversation::find()
            ->where(['object_type' => File::TYPE_BUSINESS, 'object_id' => $business_id])
            ->all();
        $business = Business::find()->where(['id' => $business_id, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('index', [
            'conversations' => $conversations,
            'business' => $business,
        ]);
    }

    public function actionView($conversation_id)
    {
        if (!($conversation_id = (int)$conversation_id)) {
            throw new HttpException(404);
        }
        /** @var QuestionConversation $conversation */
        $conversation = QuestionConversation::find()->where(['id' => $conversation_id])->one();
        $questions = $conversation->getQuestions()->orderBy(['created_at' => SORT_ASC])->all();

        if ($conversation->status === QuestionConversation::STATUS_NEW) {
            $conversation->status = QuestionConversation::STATUS_RECEIVED;
            $conversation->updateAttributes(['status' => QuestionConversation::STATUS_RECEIVED]);
        } elseif ($conversation->status === QuestionConversation::STATUS_REPLIED) {
            $conversation->status = QuestionConversation::STATUS_RECEIVED;
            $conversation->updateAttributes(['status' => QuestionConversation::STATUS_RECEIVED]);
        }
        
        return $this->render('view', ['questions' => $questions, 'conversation_id' => $conversation_id, 'conversation' => $conversation]);
    }

    public function actionNewQuestion()
    {
        $conversation_id = (int)Yii::$app->request->post('conversation_id');
        $message = Yii::$app->request->post('message');
        $user = Yii::$app->user->identity;

        if (!$message or !$message or !$user) {
            throw new HttpException(404);
        }

        /** @var QuestionConversation $conversation */
        $conversation = QuestionConversation::find()->where(['id' => $conversation_id])->one();
        if (!$conversation) {
            throw new HttpException(404);
        }

        $question = new Question([
            'text' => $message,
            'user_id' => $user->id,
            'conversation_id' => $conversation_id,
        ]);

        if ($question->save()) {
            $conversation->updateAttributes(['status' => QuestionConversation::STATUS_REPLIED]);
        }

        $this->redirect(Url::to(['conversation/view', 'conversation_id' => $conversation_id]));
    }
}

