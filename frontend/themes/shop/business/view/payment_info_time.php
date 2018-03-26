<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use common\models\UserPaymentType;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use yii\helpers\Html;

$site_url = null;
if ($model->site) {
    $site_url = 'http://' . str_replace(['http://', 'https://'], '', $model->site);
}

$emails = [];
if ($model->email) {
    $emails = explode(' ', $model->email);
    foreach ($emails as &$email) {
        $email = trim($email, ' ,;');
    }
}
?>

<div class="listing-detail-section" id="listing-detail-section-payment-info-time">
    <h2 class="page-header">Детали</h2>

    <div class="listing-detail-contact">
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li class="payment-type">
                        <strong class="key">Cпособы оплаты: </strong>
                        <?php foreach (UserPaymentType::getAll($model->idUser) as $key => $payment) : ?>
                            <span style="color: black"><?= $payment ?></span>
                        <?php endforeach; ?>
                    </li>
                    <li class="work-time">
                        <strong class="key">Время работы: </strong>
                        <span style="color: black"><?= BusinessFormatTime::widget(['id' => $model->id, 'format' => false]) ?></span>
                    </li>

                    <?php if ($emails) : ?>
                        <li class="email">
                            <strong class="key"><?= Yii::t('business', 'E-mail:') ?></strong>

                            <?php foreach ($emails as $email) : ?>
                                <span class="value" itemprop="email"><?= Html::a($email, "mailto:$email") ?></span>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>

                    <?php if ($site_url) : ?>
                        <li class="website">
                            <strong class="key"><?= Yii::t('business', 'Site') ?></strong>
                            <span class="value">
                                <?= Html::a($model->site, $site_url, ['target' => '_blank']) ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->skype) : ?>
                        <li class="skype">
                            <strong class="key"><?= Yii::t('business', 'Skype') ?></strong>
                            <span class="value"><?= $model->skype ?></span>
                        </li>
                    <?php endif;?>
                </ul>
            </div>

            <div class="col-md-6">
                <ul>
                    <?php if ($model->address) : ?>
                        <li class="address">
                            <strong class="key"><?= Yii::t('business', 'Address') ?></strong>
                            <?php foreach ($model->address as $address) : ?>
                                <?php if (isset($address->street)): ?>
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"
                                          class="value">
                                    <span itemprop="streetAddress"><?= $address->street ?></span>,
                                    <span itemprop="addressLocality"><?= $address->city ?></span>,
                                    <span itemprop="addressCountry"><?= $address->country ?></span>
                                    </span>
                                <?php else: ?>
                                    <span class="value" itemprop="streetAddress" itemscope itemtype="http://schema.org/PostalAddress"><?= $address->address ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
