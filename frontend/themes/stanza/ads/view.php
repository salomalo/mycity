<?php
/**
 * @var \common\models\Ads $model
 * @var $isEmptyCart
 * @var $business \common\models\Business
 * @var $count
 */
use frontend\controllers\ShoppingCartController;
use frontend\extensions\StanzaBottomProduct\StanzaBottomProduct;
use frontend\extensions\StanzaLatestBlog\StanzaLatestBlog;
use yii\helpers\Html;
use yii\helpers\Url;

$isInBasket = ShoppingCartController::isInBasket($model->_id)
?>

<div id="content" class="productDetail">
        <div class="container">
            <hr class="productTop">
            <div class="stripe">
                <div class="row">
                    <?= $this->render('view/gallery', ['model' => $model]) ?>

                    <div class="col-sm-6 col-xs-12">
                        <div class="product-content">
                            <h3 class="text-inherit cl_000000"><?= $model->title ?></h3>
                            <div class="breadcrumb">
                                <ul class="clearfix">
                                    <li>
                                        <a href="<?= Url::to(['/business/view', 'alias' => "{$business->id}-{$business->url}"]) ?>"><?= $business->title?></a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/business/goods', 'alias' => "{$business->id}-{$business->url}"]) ?>">Магазин</a>
                                    </li>
                                    <li><?= $model->title ?></li>
                                </ul>
                            </div><!-- ( BREAD CRUMB END ) -->
                            <br><br>
                            <div class="productPrice">
                                <?php if ($model->price) : ?>
                                    <?php if ($model->discount) : ?>
                                        <h2 class="cl_000000"><?= $model->price * (1 - $model->discount / 100) ?>
                                            грн.</h2>
                                    <?php else: ?>
                                        <h2 class="cl_000000"><?= $model->price ?> грн.</h2>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div><!-- ( PRODUCT PRICE END ) -->
                            <br>
                            <div class="stars">
                                <span class="starsimgRating"></span>
                            </div><!-- ( STARS END ) -->
                            <br><br>
                            <?= $model->description ?>
                            <br><br>
                            <div class="productQuantity productFormOption">
                                <form method="post" action="<?= Url::to(['/shopping-cart/add-shopping-cart'])?>" class="clearfix">
                                    <div class="sp-quantity proQunter clearfix">
                                        <div class="sp-minus qtyminus"><a class="qtyClick" href="#"
                                                                          data-multi="-1">-</a></div>
                                        <div class="sp-input qty">
                                            <input type="text" class="quntity-input" value="1"/>
                                        </div>
                                        <input type="hidden" id="inputId"  name="id" value="<?= $model->_id ?>">
                                        <input type="hidden" id="redirect"  name="redirect" value="index">
                                        <div class="sp-plus qtyplus"><a class="qtyClick" href="#" data-multi="1">+</a>
                                        </div>
                                    </div><!-- ( PRO QUNTER END ) -->
                                    <button class="btn-custom-3" type="submit"> <?= $isInBasket ? 'Убрать с корзины' : 'В коризну' ?></button>
                                </form>
                            </div><!-- ( PRODUCT QUANTITY END ) -->
                            <br><br>
                            <?php if (isset($model->category)) : ?>
                                <div class="smallCategories">Категории:
                                    <?= Html::a($model->category->title, Url::to(['/business/goods', 'alias' => "{$business->id}-{$business->url}", 'urlCategory' => $model->category->url])) ?>
                                </div><!-- ( SMALL CATEGORIES END ) -->
                            <?php endif; ?>
                            <br><br>
                            <!-- ( PRODUCT SHARE START ) -->
<!--                            <div class="product_share">-->
<!--                                <p>Поделиться с друзьями:</p>-->
<!--                                <ul class="social_links">-->
<!--                                    <li><a href="#"><i class="fa fa-facebook-square"></i>&nbsp;</a></li>-->
<!--                                    <li><a href="#"><i class="fa fa-twitter-square"></i>&nbsp;</a></li>-->
<!--                                    <li><a href="#"><i class="fa fa-linkedin-square"></i>&nbsp;</a></li>-->
<!--                                    <li><a href="#"><i class="fa fa-google-plus-square"></i>&nbsp;</a></li>-->
<!--                                    <li><a href="#"><i class="fa fa-pinterest-square"></i>&nbsp;</a></li>-->
<!--                                    <li><a href="#"><i class="fa fa-instagram"></i>&nbsp;</a></li>-->
<!--                                </ul>-->
<!--                            </div>-->
                            <!-- ( PRODUCT SHARE END ) -->
                        </div><!-- ( PRODUCT CONTENT END ) -->
                    </div>
                </div><!-- ( ROW END ) -->
            </div><!-- ( STRIPE END ) -->

            <div class="stripe">
                <div class="product-details">
                    <div class="tabs_container">
                        <ul class="nav nav-pills" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#desc" aria-controls="desc" role="tab" data-toggle="tab">Описание</a>
                            </li>
                            <li role="presentation">
                                <a href="#add-info" aria-controls="add-info" role="tab" data-toggle="tab">Характеристики</a>
                            </li>
                            <li role="presentation">
                                <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Отзывы</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="desc">
                                <?= $model->description ?>
                            </div>

                            <?= $this->render('view/add_info', ['model' => $model]) ?>

                            <?= $this->render('view/comments', ['model' => $model]) ?>
                        </div> <!-- ( TAB CONTENT END ) -->
                    </div><!-- ( TABS CONTAINER END ) -->
                </div><!-- ( PRODUCT DETAILS END ) -->
            </div><!-- ( STRIPE END ) -->
        </div>

        <?= $this->render('view/related_products', ['model' => $business]) ?>

        <?= StanzaBottomProduct::widget(['businessModel' => $business]) ?>

    </div><!-- ( CONTENT END ) -->
