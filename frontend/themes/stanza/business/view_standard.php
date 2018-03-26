<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 * @var $isShowDescription boolean
 * @var $s string
 * @var $listCategory array
 */
use frontend\extensions\GoodCategory\GoodCategory;
use frontend\extensions\StanzaLatestBlog\StanzaLatestBlog;
use frontend\themes\stanza\AppAssets;
use yii\helpers\Url;

$bundle = AppAssets::register($this);
?>



    <div id="banner" class="stripe banner">
        <!-- START REVOLUTION SLIDER 5.0.7 -->
        <div class="rev_slider_wrapper">
            <div id="slider1" class="rev_slider" data-version="5.0.7">
                <ul>
                    <li data-transition="fade" data-thumb="<?= $bundle->baseUrl . '/images/banner_sales.png' ?>" data-title="Super Big Sale">
                        <!-- MAIN IMAGE -->
                        <img src="<?= $bundle->baseUrl . '/images/banner_sales.png' ?>" alt="" width="1920" height="617">

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme rs-parallaxlevel-0 slider_text slider_right"
                             id="slide-2-layer-1"
                             data-x="['right','center','center','center']" data-hoffset="['315','0','0','0']"
                             data-y="['top','top','top','top']" data-voffset="['190','130','110','80']"
                             data-fontsize="['49','33','33','28']"
                             data-lineheight="['40','30','25','30']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;"
                             data-transform_in="y:50px;opacity:0;s:2000;e:Power3.easeOut;"
                             data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index:5;white-space:nowrap;color:#000;">До
                        </div>

                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption tp-resizeme rs-parallaxlevel-0 slider_text slider_right"
                             id="slide-2-layer-3"
                             data-x="['right','center','center','center']" data-hoffset="['238','0','0','0']"
                             data-y="['top','middle','middle','middle']" data-voffset="['247','0','0','0']"
                             data-fontsize="['106','70','70','45']"
                             data-lineheight="['100','70','70','50']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-letterspacing="-3"
                             data-transform_idle="o:1;"
                             data-transform_in="y:[-100%];z:0;rZ:35deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;"
                             data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-splitin="chars"
                             data-splitout="none"
                             data-responsive_offset="on"
                             data-elementdelay="0.05"
                             style="z-index:6;white-space:nowrap;color:#e85200;">40%
                        </div>

                        <!-- LAYER NR. 4 -->
                        <div class="tp-caption tp-resizeme rs-parallaxlevel-0 slider_text slider_right"
                             id="slide-2-layer-4"
                             data-x="['right','left','left','center']" data-hoffset="['0','651','563','434']"
                             data-y="['top','top','top','top']" data-voffset="['305','483','377','180']"
                             data-fontsize="['31','25','20','25']"
                             data-lineheight="['25','30','25','30']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-visibility="['on','on','on','on']"
                             data-transform_idle="o:1;"
                             data-transform_in="x:50px;opacity:0;s:1500;e:Power3.easeInOut;"
                             data-transform_out="x:[-100%];s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="2000"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index:26;white-space:nowrap;color:#000;">скидки на товары
                        </div>

                        <!-- LAYER NR. 5 -->
                        <div class="tp-caption rs-parallaxlevel-0 slider_text1 slider_right"
                             id="slide-2-layer-5"
                             data-x="['right','right','right','center']" data-hoffset="['0','30','30','0']"
                             data-y="['top','bottom','bottom','middle']" data-voffset="['365','30','30','-44']"
                             data-fontsize="['74','25','20','25']"
                             data-lineheight="['70','30','25','30']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;"
                             data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:1500;e:Power4.easeInOut;"
                             data-transform_out="auto:auto;s:1000;"
                             data-start="2500"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             data-responsive="on"
                             style="z-index:22;white-space:nowrap;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;">Распродажа
                        </div>

                        <!-- LAYER NR. 7 -->
                        <div class="tp-caption rs-parallaxlevel-0 slider_borderbtn1 slider_rightM"
                             id="slide-2-layer-7"
                             data-x="['right','left','left','center']" data-hoffset="['210','522','399','0']"
                             data-y="['bottom','top','top','top']" data-voffset="['105','248','218','188']"
                             data-fontsize="['20','25','20','25']"
                             data-width="194"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;"
                             data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                             data-style_hover="c:rgba(232,82,0,1.00);bg:rgba(255,255,255,1.00);cursor:pointer;"
                             data-transform_in="x:[100%];z:0;rX:0deg;rY:0deg;rZ:0deg;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2500;e:Power3.easeInOut;"
                             data-transform_out="auto:auto;s:1000;e:Power2.easeInOut;"
                             data-start="3500"
                             data-splitin="none"
                             data-splitout="none"
                             data-actions='[{"event":"click","action":"jumptoslide","slide":"next","delay":""}]'
                             data-responsive_offset="on"
                             data-responsive="on"
                             style="z-index:9;white-space:nowrap;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;background-color:rgba(232,82,0,1.00);">В магазин
                        </div>
                    </li>
                </ul>
            </div><!-- END REVOLUTION SLIDER -->
        </div><!-- END OF SLIDER WRAPPER -->
    </div><!-- ( BANNER END ) -->



    <div id="content">

        <?= $this->render('view/featured_products', ['model' => $model, 'alias' => 'featured']) ?>

        <?= GoodCategory::widget(['businessModel' => $model, 'view' => 'stanza']) ?>

        <div class="stripeM bg_image bg1">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-offset-4 col-md-8 testimonials_widget">
                        <div class="testimonial_text">
                            Перебрав не один предложенный вариант по созданию интернет-магазина, решили остановиться на его создании на портале CityLife.info.
                            Преимущества данного предложения перед остальными видны сразу же. Это легкость создания, стильный дизайн, доступность к изложению всего необходимого с учетом пожеланий заказчика, процесс оформления легок и понятен каждому.
                        </div><!-- ( TESTIMONIAL TEXT END ) -->
                        <div class="stars">
                            <span class="starsimgRating"></span>
                        </div><!-- ( STARS END ) -->
                        <div class="testimonial_author">
                            <span class="testimonial_image no_image"></span>
                            veronika
                        </div><!-- ( TESTIMONIAL AUTHOR END ) -->
                    </div><!-- ( TESTIMONIAL WIDGET END ) -->
                </div>
            </div>
        </div><!-- ( STRIPE END ) -->

        <?= $this->render('view/new_products', ['model' => $model, 'alias' => 'new']) ?>

        <?= $this->render('view/best_sellers', ['model' => $model, 'alias' => 'best_sellers']) ?>

        <?= StanzaLatestBlog::widget(['businessModel' => $model]) ?>
    </div><!-- ( CONTENT END ) -->


