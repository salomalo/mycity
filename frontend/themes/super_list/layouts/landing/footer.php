<?php
/**
 * @var \yii\web\View $this
 */
use common\extensions\Counters\Counters;

?>

<footer id="footer" class="footer light-text">
    <div class="container">
        <div class="footer-content row">

            <div class="col-sm-5 social-wrap col-xs-12">
                <div class="opacity-square">
                    <strong class="heading">НАШИ КОНТАКТЫ</strong>
                    <ul class="list-unstyled">
                        <li>
                            <span class="icon icon-chat-messages-14"></span>
                            <a href="mailto:support@citylife.info">support@citylife.info</a>
                        </li>
                        <li>
                            <span class="icon icon-seo-icons-17"></span>
                            +380688843372
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-5 social-wrap col-xs-12">
                <div class="opacity-square">
                    <strong class="heading">Нас считают</strong>
                    <div class="counter">
                        <?php if (YII_ENV === 'prod') : ?>
                            <?= Counters::widget(['app' => 'frontend']) ?>
                        <?php endif?>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-xs-12"></div>
        </div>
    </div>
    <div class="copyright">CityLife 2016. All rights reserved.</div>
</footer>