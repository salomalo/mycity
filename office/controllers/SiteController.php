<?php
namespace office\controllers;

use common\models\Ads;
use common\models\File;
use common\models\Invoice;
use common\models\Notification;
use common\models\Orders;
use common\models\OrdersAds;
use common\models\QuestionConversation;
use frontend\models\SignupForm;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Site controller
 */
class SiteController extends DefaultController
{
    public $layout = 'site';
    public $breadcrumbs = [];

    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex()
    {
        $myOrders = Orders::find()->where(['idUser' => Yii::$app->user->id])->count();
        $orderInBusiness = OrdersAds::find()
            ->select(['pid'])
            ->where(['idUser' => Yii::$app->user->id])
            ->distinct(['pid'])
            ->count();
        $ads = (Yii::$app->user->id) ? (Ads::find()->where(['idUser' => (int)Yii::$app->user->id])->count()) : 0;

        return $this->render('index', [
            'myOrders' => $myOrders,
            'orderInBusiness' => $orderInBusiness,
            'ads' => $ads,
        ]);
    }

    public function actionNotification()
    {
        if (!($user = Yii::$app->user->identity) or !Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }

        $result = [];

        /** @var QuestionConversation[] $questions */
        $questions = QuestionConversation::find()->select(['id', 'title'])
            ->where([
                'status' => QuestionConversation::STATUS_NEW,
                'owner_id' => $user->id,
                'object_type' => [File::TYPE_ADS, File::TYPE_BUSINESS],
            ])
            ->orderBy('created_at')
            ->asArray()->all();

        foreach ($questions as $question) {
            $result[] = '<li>' . Html::a('<i class="fa fa-warning text-yellow"></i> Новый вопрос', ['conversation/view', 'conversation_id' => $question['id']]) . '</li>';
        }

        $notifications = Notification::find()->where([
            'status' => Notification::STATUS_NEW,
            'sender_id' => $user->id,
        ])->all();

        foreach ($notifications as $not) {
            $result[] = '<li>' . Html::a('<i class="fa fa-warning text-yellow"></i> ' . $not->title, ['/notification/view', 'id' => $not->id]) . '</li>';
        }

        return implode(PHP_EOL, $result);
    }

    public function actionNotificationCount()
    {
        if (!($user = Yii::$app->user->identity) or !Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        $question = QuestionConversation::find()->select(['object_type', 'count' => 'COUNT(*)'])
            ->where([
                'status' => QuestionConversation::STATUS_NEW,
                'owner_id' => $user->id,
                'object_type' => array_keys(QuestionConversation::$question_types),
            ])
            ->orWhere([
                'status' => QuestionConversation::STATUS_REPLIED,
                'user_id' => $user->id,
                'object_type' => array_keys(QuestionConversation::$support_types),
            ])
            ->groupBy('object_type')
            ->asArray()->all();

        $question = ArrayHelper::map($question, 'object_type', 'count');

        $support = (isset($question[File::TYPE_SUPPORT]) ? $question[File::TYPE_SUPPORT] : 0) +
            (isset($question[File::TYPE_SUPPORT_ADS]) ? $question[File::TYPE_SUPPORT_ADS] : 0) +
            (isset($question[File::TYPE_SUPPORT_ADVERT]) ? $question[File::TYPE_SUPPORT_ADVERT] : 0) +
            (isset($question[File::TYPE_SUPPORT_BUSINESS]) ? $question[File::TYPE_SUPPORT_BUSINESS] : 0);
        $invoices = Invoice::find()->where(['user_id' => Yii::$app->user->id, 'paid_status' => Invoice::PAID_NO])->count();
        $business = $invoices + (isset($question[File::TYPE_BUSINESS]) ? (int)$question[File::TYPE_BUSINESS] : 0);

        $result = [
            'notification' => ($sum = array_sum($question)) ? $sum : '',
            'ads' => isset($question[File::TYPE_ADS]) ? $question[File::TYPE_ADS] : '',
            'business' => $business ? $business : '',
            'support' => $support ? $support : '',
        ];

        return Json::encode($result);
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {

            return $this->render('error', [
                'exception' => $exception,
                'statusCode' => \Yii::$app->errorHandler->code,
            ]);
        }
    }
}
