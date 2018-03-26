<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

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
<div class="listing-detail-section" id="listing-detail-section-contact">
    <h2 class="page-header"><?= Yii::t('business', 'Contacts') ?></h2>

    <div class="listing-detail-contact">
        <div class="row">
            <div class="col-md-6">
                <ul>
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

                    <?php if ($model->phone) : ?>
                        <li class="phone">
                            <strong class="key"><?= Yii::t('business', 'Phone') ?></strong>
                            <span class="value" itemprop="telephone"><?= preg_replace("/\r\n|\r|\n/",'<br/>',$model->phone); ?></span>
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