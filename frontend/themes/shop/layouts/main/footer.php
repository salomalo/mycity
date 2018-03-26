<?php
/**
 * @var $this \yii\web\View
 * @var $businessModel \common\models\Business
 */

use common\extensions\Counters\Counters;
use common\models\Ads;
use frontend\extensions\VkGroup\VkGroup;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<a href="#" id="top">
    <div class="action-bar-title"><i class="glyphicon glyphicon-chevron-up"></i></div>
</a>

<div><div id="popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B" class="b-popup b-popup_type_hint-with-closer b-popup_size_normal hidden" data-qaid="popup_hint" name="popup" style="margin-top: -56px;; left: 483px; z-index: 10000003;">
        <div class="b-popup__tail b-popup__tail_orientation_south" style="left: 161.5px; top: 56px;"></div>


        <div class="b-popup__body h-font-size-13">
            Простое и быстрое создание сайтов и интернет-магазинов&nbsp;&nbsp;&nbsp;
            <a href="<?= Url::to(['/landing/index']) ?>" class="b-button h-vertical-middle h-inline-block">
                <span class="b-button__aligner"></span>
                <span class="b-button__text h-mh-10">
                                Создать сайт
                            </span>
            </a>

        </div>
    </div>
</div>

<div class="b-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
    <div class="b-footer__counters">
        <?php if (YII_ENV === 'prod') : ?>
            <?= Counters::widget(['app' => 'frontend']) ?>
        <?php endif; ?>
    </div>
    <div class="b-footer__row">
        <a class="js-popup" href="<?= Url::to(['/landing/index'])?>" id="834DAD95-85CF-4800-8D5E-C2205220ED21">Сайт создан на платформе CityLife</a>
    </div>

    <div class="b-footer__row">
        <?= $businessModel->title ?>
        | <a href="#" rel="nofollow">Пожаловаться на содержимое</a>
    </div>
</div>

<div>
    <div class="bgl-overlay hidden" style="z-index: 90000; display: block;" data-reactid=".i">
        <div class="bgl-overlay__dialog" data-qaid="popup-overlay" data-reactid=".i.0">
            <div class="bgl-overlay__close-button" data-qaid="close-btn" data-reactid=".i.0.0"></div>
            <div class="bgl-overlay-phones" data-reactid=".i.0.1">
                <div class="bgl-overlay-title qa-overlay-title" data-reactid=".i.0.1.0">Свяжитесь с продавцом
                </div>
                <div id="bgl-overlay-seller-phone">
                    <?= preg_replace("/\r\n|\r|\n/", '<br/>', $businessModel->phone); ?>
                </div>
            </div>
        </div>
    </div>
</div>