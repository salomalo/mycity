<?php
/**
 * @var $this \yii\web\View
 * @var $idFloor string
 * @var $productCategory \common\models\ProductCategory
 * @var $child \common\models\ProductCategory[]
 * @var $key integer
 * @var $newProducts \common\models\Ads[]
 * @var $onSaleProducts \common\models\Ads[]
 * @var $topRatingProducts \common\models\Ads[]
 * @var $specialProduct \common\models\Ads[]
 * @var $business \common\models\Business
 */
use yii\helpers\Url;

$alias = "{$business->id}-{$business->url}";
?>

<div class="container">
        <div class="block-title ">
                            <span class="title">
                                <span class="text"><?= $productCategory->title ?></span>
                            </span>
            <div class="links dropdown">
                <button class="dropdown-toggle"  type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu" >
                    <ul  >
                        <li role="presentation" class="active"><a href="#<?= $idFloor ?>-1"  role="tab" data-toggle="tab">Спец. предложения</a></li>
                        <li role="presentation"><a href="#<?= $idFloor ?>-2"  role="tab" data-toggle="tab">Новые поступления <span class="label-cat"><?= count($newProducts) ?></span></a></li>
                        <li role="presentation"><a href="#<?= $idFloor ?>-3"  role="tab" data-toggle="tab">Топ просмотров </a></li>
                        <li role="presentation"><a href="#<?= $idFloor ?>-4"  role="tab" data-toggle="tab">На скидке </a></li>
                    </ul>
                </div>
            </div>
            <div class="actions">
                <a href="<?= (($key) <= 0) ? '' : '#floor0-' . ($key)?>" class="action action-up"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
                <a href="#floor0-<?= $key+2 ?>" class="action action-down"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="block-content">

            <div class="col-categori">
                <ul>
                    <?php foreach ($child as $ch) : ?>
                        <li><a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => $ch->url])?>"><?= $ch->title ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a class="btn-show-cat btn-cat">All categories <i aria-hidden="true" class="fa fa-angle-double-right"></i></a>
            </div>


            <div class="col-banner">
                <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => $productCategory->url])?>" class="box-img"><img src="<?= Yii::$app->files->getUrl($productCategory, 'image') ?>" alt="baner-floor"></a>
            </div>



            <div class="col-products tab-content">

                <!-- tab 2-->
                <div class="tab-pane active in  fade " id="<?= $idFloor ?>-1" role="tabpanel">
                    <div class="owl-carousel"
                         data-nav="true"
                         data-dots="false"
                         data-margin="0">

                        <?php foreach ($specialProduct as $key => $ads) : ?>
                            <?= $key % 2 === 0 ? '<div class="item">' : ''?>
                            <?= $this->render('item', ['model' => $ads, 'alias' => 'spec']) ?>
                            <?= $key % 2 === 1 ? '</div>' : ''?>
                        <?php endforeach; ?>
                        <?= count($specialProduct) % 2 === 1 ? '</div>' : '' ?>
                    </div>
                </div>

                <!-- tab 3 -->
                <div class="tab-pane  fade" id="<?= $idFloor ?>-2" role="tabpanel">
                    <div class="owl-carousel"
                         data-nav="true"
                         data-dots="false"
                         data-margin="0">

                        <?php foreach ($newProducts as $key => $ads) : ?>
                            <?= $key % 2 === 0 ? '<div class="item">' : ''?>
                            <?= $this->render('item', ['model' => $ads, 'alias' => 'new']) ?>
                            <?= $key % 2 === 1 ? '</div>' : ''?>
                        <?php endforeach; ?>
                        <?= count($newProducts) % 2 === 1 ? '</div>' : '' ?>
                    </div>
                </div>

                <!-- tab 4 -->
                <div class="tab-pane  fade" id="<?= $idFloor ?>-3" role="tabpanel">
                    <div class="owl-carousel"
                         data-nav="true"
                         data-dots="false"
                         data-margin="0">

                        <?php foreach ($topRatingProducts as $key => $ads) : ?>
                            <?= $key % 2 === 0 ? '<div class="item">' : ''?>
                            <?= $this->render('item', ['model' => $ads, 'alias' => 'top']) ?>
                            <?= $key % 2 === 1 ? '</div>' : ''?>
                        <?php endforeach; ?>
                        <?= count($topRatingProducts) % 2 === 1 ? '</div>' : '' ?>
                    </div>
                </div>

                <!-- tab 5 -->
                <div class="tab-pane fade " id="<?= $idFloor ?>-4" role="tabpanel">
                    <div class="owl-carousel"
                         data-nav="true"
                         data-dots="false"
                         data-margin="0">

                        <?php foreach ($onSaleProducts as $key => $ads) : ?>
                            <?= $key % 2 === 0 ? '<div class="item">' : ''?>
                            <?= $this->render('item', ['model' => $ads, 'alias' => 'sale']) ?>
                            <?= $key % 2 === 1 ? '</div>' : ''?>
                        <?php endforeach; ?>
                        <?= count($onSaleProducts) % 2 === 1 ? '</div>' : '' ?>
                    </div>
                </div>

            </div>

        </div>

    </div>