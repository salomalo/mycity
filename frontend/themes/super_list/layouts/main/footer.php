<?php
/**
 * @var $this \yii\web\View
 */

use common\extensions\Counters\Counters;
use common\models\Ads;
use frontend\extensions\VkGroup\VkGroup;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<footer class="footer">
    <div class="footer-top">
        <div class="footer-area">
            <div class="container">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="widget widget_nav_menu">
                            <?php if ($action != 'about-city') : ?>
                                <?= VkGroup::widget() ?>
                            <?php endif;?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="footer-top-inner">
                <div class="footer-first">
                    <div class="widget widget_nav_menu">
                        <div class="menu-footer-menu-container">
                            <ul id="menu-footer-menu" class="menu">
                                <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <?= Html::a(Yii::t('app', 'Our_contacts'), ['/'], [
                                        'onclick' => 'contact("' . Url::to(['/site/showmodal-contact', 'type' => 'contact']) . '");return false;'
                                    ]) ?>
                                </li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <?= Html::a(Yii::t('app', 'Advertising_on_the_website'), ['/site/reklama']) ?>
                                </li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <?= Html::a(Yii::t('app', 'Complaints_and_suggestions'), ['/'], [
                                        'onclick' => 'contact("' . Url::to(['/site/showmodal-complaint', 'type' => 'complaints']) . '");return false;'
                                    ]) ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-second">
                    <div class="counter">
                        <?php if (YII_ENV === 'prod') : ?>
                            <?= Counters::widget(['app' => 'frontend']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>