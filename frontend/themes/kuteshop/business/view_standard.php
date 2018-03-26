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
use common\models\Ads;
use common\models\ProductCategory;
use frontend\extensions\KuteshopFloorProduct\KuteshopFloorProduct;
use frontend\themes\kuteshop\AppAssets;

$categories = ProductCategory::find()->roots()->all();
$resultModels = [];
foreach ($categories as $cat) {
    //Проверяем является ли категория листом
    /** @var ProductCategory $cat */
    if ($cat->isLeaf()) {
        $ads = Ads::findOne(['idCategory' => $cat->id, 'idBusiness' => $model->id]);
        if ($ads) {
            $resultModels[] = $model;
        }
    } else {
        /** @var ProductCategory[] $child */
        $child = $cat->children()->all();
        foreach ($child as $ch) {
            //если есть обьвления в детей то добавляет категория в массив
            $ads = Ads::findOne(['idCategory' => $ch->id, 'idBusiness' => $model->id]);
            if ($ads) {
                $resultModels[] = $cat;
                break;
            }
        }
    }
}

$bundle = AppAssets::register($this);
?>

<!-- MAIN -->
<main class="site-main">

        <div class="block-section-top block-section-top5">
            <div class="container">
                <div class="box-section-top">

                    <!-- block slide top -->
                    <div class="block-slide-main slide-opt-5">

                        <!-- slide -->
                        <div class="owl-carousel "
                             data-nav="true"
                             data-dots="false"
                             data-margin="0"
                             data-items='1'
                             data-autoplayTimeout="700"
                             data-autoplay="true"
                             data-loop="true">
                            <div class="item">
                                <div class="img-lag" style="left: 0">
                                    <img src="<?= $bundle->baseUrl . '/images/01.jpg' ?>" alt="girl" class="img-2" width="600" height="450">
                                </div>
                                <div class="img-sm" style="right: 0;bottom: 0">
                                    <img src="<?= $bundle->baseUrl . '/images/02.jpg' ?>" alt="img" class="img-1" width="463" height="257">
                                </div>
                                <div class="description">
                                    <span class="subtitle " ><span>РАСПРОДАЖА</span></span>
                                    <span class="title">До <span>50% скидка</span><i class="fa fa-long-arrow-down" aria-hidden="true"></i> </span>
                                    <span class="des"><span>Самое лучшее сезона</span><span class="view-opt5"><a href="" onclick="return false">Перейти</a></span></span>
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-lag" style="left: 0">
                                    <img src="<?= $bundle->baseUrl . '/images/03.jpg' ?>" alt="girl" class="img-2" width="600" height="450">
                                </div>
                                <div class="img-sm" style="right: 0;bottom: 0">
                                    <img src="<?= $bundle->baseUrl . '/images/04.jpg' ?>" alt="img" class="img-1" width="463" height="257">
                                </div>
                                <div class="description">
                                    <span class="subtitle " ><span>РАСПРОДАЖА</span></span>
                                    <span class="title">До <span>50% скидка</span><i class="fa fa-long-arrow-down" aria-hidden="true"></i> </span>
                                    <span class="des"><span>Самое лучшее сезона</span><span class="view-opt5"><a href="" onclick="return false">Перейти</a></span></span>
                                </div>
                            </div>
                        </div> <!-- slide -->

                    </div><!-- block slide top -->


                </div>
            </div>
        </div>

        <div class="block-banner-lag effect-banner2">
            <div class="container">
                <div class="clearfix">
                    <div class="col-sm-6 no-padding">
                        <a href="" onclick="return false" class="box-img"><img src="<?= $bundle->baseUrl . '/images/man_collection_boys_01.jpg' ?>" alt="banner"></a>
                    </div>
                    <div class="col-sm-6 no-padding">
                        <a href="" onclick="return false" class="box-img"><img src="<?= $bundle->baseUrl . '/images/women_collection_girls_001.jpg' ?>" alt="banner"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix" style="background-color: #eaeaea;margin-bottom: 30px; padding-top:30px;">

            <?php foreach ($resultModels as $key => $category) : ?>
                <div class="block-floor-products block-floor-products-opt2 floor-products7" id="floor0-<?= $key + 1 ?>">
                    <?= KuteshopFloorProduct::widget([
                        'idFloor' => 'floor' . ($key+1),
                        'key' => $key,
                        'productCategory' => $category,
                        'business' => $model,
                    ]) ?>
                </div><!-- block -floor -products / floor i :-->
            <?php endforeach; ?>

        </div>

    </main><!-- end MAIN -->

