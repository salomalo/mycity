<?php
/**
 * @var $this \yii\web\View
 * @var $active string
 */

use common\models\Invoice;
use yii\helpers\Html;
?>

<ul class="nav nav-tabs">
    <li class="<?= ($active === 'view') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'General_information'), ['/business/view', 'id' => $id]) ?>
    </li>
    <li class="<?= ($active === 'update') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Editing'), ['/business/update', 'id' => $id]) ?>
    </li>
    <li class="<?= ($active === 'add-gallery') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Add_gallery'), ['/business/add-gallery', 'id' => $id]) ?>
    </li>
    <li class="<?= ($active === 'afisha') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Afisha'), ['/afisha/index', 'idCompany' => $id]) ?>
    </li>
    <li class="<?= ($active === 'action') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Promotions'), ['/action/index', 'idCompany' => $id]) ?>
    </li>
    <li class="<?= ($active === 'work-vacantion') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Job'), ['/work-vacantion/index', 'idCompany' => $id]) ?>
    </li>
    <li class="<?= ($active === 'ads') ? 'active' : false ?>">
        <?= Html::a(Yii::t('ads', 'Ads'), ['/ads/index', 'idCompany' => $id]) ?>
    </li>
    <li class="<?= ($active === 'post') ? 'active' : false ?>">
        <?= Html::a(Yii::t('business', 'Posts'), ['/post/index', 'idCompany' => $id]) ?>
    </li>
    <li class="<?= ($active === 'transactions') ? 'active' : false ?>">
        <?php
        $count_not_paid_invoice = Invoice::find()->where(['user_id' => (int)Yii::$app->user->id])->andWhere(['paid_status' => Invoice::PAID_NO])->andWhere(['object_id' => $id])->count();

        $count = '';
        if ($count_not_paid_invoice > 0)
            $count = '<small class="badge pull-right bg-yellow" style="margin-left: 5px;">' .  $count_not_paid_invoice . '</small>';
        ?>
        <?= Html::a(Yii::t('business', 'Invoices') . $count, ['/business/invoice', 'id' => $id]) ?>

    </li>
</ul>