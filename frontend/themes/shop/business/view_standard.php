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

use yii\widgets\Breadcrumbs;

$cf = [];
if ($model->customFieldValues) {
    foreach ($model->customFieldValues as $item) {
        $cf[$item->customField->title][] = $item->anyValue;
    }
    foreach ($cf as &$item) {
        $item = implode(', ', $item);
    }
}

?>


<?php if ($model->isCategoryCafe()): ?>
    <div class="main" itemscope itemtype="http://schema.org/Restaurant">
<?php else: ?>
    <div class="main" itemscope itemtype="http://schema.org/LocalBusiness">
<?php endif; ?>


    <div class="main-inner">
        <div class="container" style="">
            <div class="row">
                <!-- Slider -->
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Указатели -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                    </ol>
                    <!-- Контент слайда (slider wrap)-->
                    <div class="carousel-inner" style="max-height: 350px !important;">
                        <div class="item active">
                            <div class="fig">
                                <img src="/img/shop/01.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/02.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/03.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/04.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/05.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Элементы управления -->
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                                <?= Breadcrumbs::widget([
                                    'tag' => 'ol',
                                    'options' => ['class' => 'breadcrumb'],
                                    'homeLink' => false,
                                    'links' => $breadcrumbs,
                                ]); ?>
                            <?php endif; ?>
                        </div><!-- /.document-title -->

                        <div class="content">
                            <?= $this->render('view/new_ads', ['model' => $model]) ?>

                            <?= $this->render('view/popular_category', ['model' => $model]) ?>
                        </div>
                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $model, 'pid' => null]) ?>

            </div>
        </div>
    </div>
</div>