<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $alias string
 */
use common\models\Ads;

/** @var Ads[] $ads */
$adsModels = $model->getAds()->orderBy(['_id' => SORT_DESC])->limit(8)->all();
?>

<?php if ($adsModels) : ?>
<div class="stripe">
    <div class="container">
        <h3 class="dashStyle">НОВЫЕ ТОВАРЫ</h3>
        <div class="productsRow row">
            <ul class="clearfix prodCarousel">
                <?php foreach ($adsModels as $ads) : ?>
                    <li style="width: 100%;">
                        <div class="col-xs-12">
                            <?= $this->render('_short_ads', ['model' => $ads, 'business' => $model, 'alias' => $alias]) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- ( PRODUCTS ROW END ) -->
    </div>
</div><!-- ( STRIPE END ) -->
<?php endif; ?>