<?php
namespace office\controllers;

use common\models\File;
use common\models\Question;
use common\models\QuestionConversation;
use yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class SupportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' =>[
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $models = QuestionConversation::find()->where([
            'user_id' => Yii::$app->user->id,
            'object_type' => array_keys(QuestionConversation::$support_types)
        ])->all();

        return $this->render('index', ['models' => $models]);
    }

    public function actionView($id)
    {
        if (!($id = (int)$id)) {
            throw new HttpException(404);
        }
        /** @var QuestionConversation $conversation */
        $conversation = QuestionConversation::find()->where(['id' => $id])->one();
        $questions = $conversation->getQuestions()->orderBy(['created_at' => SORT_ASC])->all();

        if ($conversation->status === QuestionConversation::STATUS_REPLIED) {
            $conversation->status = QuestionConversation::STATUS_RECEIVED;
            $conversation->updateAttributes(['status' => QuestionConversation::STATUS_RECEIVED]);
        }

        return $this->render('view', ['questions' => $questions, 'conversation_id' => $id, 'conversation' => $conversation]);
    }

    public function actionNewQuestion()
    {
        $id = (int)Yii::$app->request->post('id');
        $message = Yii::$app->request->post('message');
        $user = Yii::$app->user->identity;

        if (!$message or !$message or !$user) {
            throw new HttpException(404);
        }

        /** @var QuestionConversation $conversation */
        $conversation = QuestionConversation::find()->where(['id' => $id])->one();
        if (!$conversation) {
            throw new HttpException(404);
        }

        $question = new Question([
            'text' => $message,
            'user_id' => $user->id,
            'conversation_id' => $id,
        ]);

        if ($question->save()) {
            $conversation->updateAttributes(['status' => QuestionConversation::STATUS_REPLIED]);
        }

        $this->redirect(Url::to(['support/view', 'id' => $id]));
    }

    public function actionCreateAjax()
    {
        $user = Yii::$app->user->identity;
        $msg = trim(strip_tags(Yii::$app->request->post('msg')));
        if (!$user or !$msg or !Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }

        $conversation = new QuestionConversation([
            'status' => QuestionConversation::STATUS_NEW,
            'object_type' => File::TYPE_SUPPORT,
            'object_id' => Yii::$app->request->referrer,
            'user_id' => $user->id,
            'title' => substr($msg, 0, 50) . '...',
        ]);
        $conversation_save = $conversation->save();

        $question = new Question([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'text' => $msg,
        ]);
        $question_save = $question->save();

        if ($conversation_save and $question_save) {
            $url = Html::a('тут', ['support/view', 'id' => $conversation->id]);
            $answer = Yii::t('app', 'Your request {here}', ['here' => $url]);
        } else {
            $answer = Yii::t('app', 'Sorry, we have an error');
        }

        return $answer;
    }
}

