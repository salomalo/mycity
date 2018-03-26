<?php
/**
 * @var $this \yii\web\View
 */

use common\models\File;
use common\models\Invoice;
use common\models\Orders;
use common\models\OrdersAds;
use common\models\QuestionConversation;
use common\models\Ticket;
use yii\helpers\ArrayHelper;

$question = QuestionConversation::find()
    ->select(['object_type', 'count' => 'COUNT(*)'])
    ->where([
        'status' => QuestionConversation::STATUS_NEW,
        'owner_id' => Yii::$app->user->identity->id,
        'object_type' => array_keys(QuestionConversation::$question_types),
    ])
    ->groupBy('object_type')
    ->asArray()->all();

$support = QuestionConversation::find()->where([
    'status' => QuestionConversation::STATUS_REPLIED,
    'user_id' => Yii::$app->user->identity->id,
    'object_type' => array_keys(QuestionConversation::$support_types),
])->count();

$question = ArrayHelper::map($question, 'object_type', 'count');

$invoices = Invoice::find()->where(['user_id' => Yii::$app->user->id, 'paid_status' => Invoice::PAID_NO])->count();
//$business = $invoices + (isset($question[File::TYPE_BUSINESS]) ? (int)$question[File::TYPE_BUSINESS] : 0);

$countOrders = Orders::find()->where(['idSeller' => Yii::$app->user->id])->count();

$menu_items = [
    [
        'icon' => 'fa fa-university',
        'title' => Yii::t('business', 'Business'), 'url' => ['/business/index'],
        //'counter' => $business,
        //'counter_id' => 'business_badge',
    ],
    [
        'icon' => 'fa fa-credit-card',
        'title' => Yii::t('app', 'Orders_in_business'),
        'url' => ['/order/index'],
        'counter' => $countOrders,
    ],
    [
        'icon' => 'fa fa-tags',
        'title' => Yii::t('app', 'Ads'), 'url' => ['/ads/index'],
        'counter' => isset($question[File::TYPE_ADS]) ? $question[File::TYPE_ADS] : null,
        'counter_id' => 'ads_badge',
    ],
    [
        'icon' => 'fa fa-key',
        'title' => Yii::t('app', 'Billing'), 'url' => ['/transactions/index'],
        'counter' => Invoice::find()->where(['user_id' => (int)Yii::$app->user->id])->andWhere(['paid_status' => Invoice::PAID_NO])->count(),
    ],
    [
        'icon' => 'fa fa-id-card-o',
        'title' => Yii::t('app', 'My_resume'),
        'url' => ['/work-resume/index']
    ],
    [
        'icon' => 'fa fa-user-circle-o',
        'title' => Yii::t('app', 'My_vacantion'),
        'url' => ['/work-vacantion/index']
    ],
    [
        'icon' => 'fa fa-question',
        'title' => Yii::t('app', 'Support'), 'url' => ['/ticket/index'],
        'counter' => Ticket::find()->where(['idUser' => Yii::$app->user->id, 'status' => Ticket::STATUS_ANSWER])->count(),
    ],
    [
        'icon' => 'fa fa-shopping-cart',
        'title' => Yii::t('app', 'My_purchases'),
        'url' => ['/my-order/index']
    ],
    [
        'icon' => 'fa fa-rocket',
        'title' => Yii::t('app', 'Advertisements'),
        'url' => ['/advertisement/index']
    ],
//    [
//        'icon' => 'fa fa-question',
//        'title' => Yii::t('app', 'Support'), 'url' => ['/support/index'],
//        'counter' => $support,
//        'counter_id' => 'support_badge',
//    ],
];
?>

<ul class="sidebar-menu">
    <?php foreach ($menu_items as $item) : ?>
        <?= $this->render('_menu_item', $item) ?>
    <?php endforeach; ?>
</ul>