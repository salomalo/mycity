<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use common\models\Ads;

/** @var Ads[] $ads */
$adsModels = $model->getAds()->orderBy(['isShowOnBusiness' => SORT_DESC])->limit(8)->all();
?>

<?php if ($adsModels) : ?>
    <div class="stripe">
        <div class="productsRow row">
            <?php foreach ($adsModels as $ads) : ?>
                <?= $this->render('_short_ads', ['model' => $ads]) ?>
            <?php endforeach; ?>
        </div><!-- ( PRODUCTS ROW END ) -->

        <div class="text-center">
            <div class="pagination">
                <a href="#" class="prevPage"><i class="fa fa-angle-left"></i></a>
                <a href="#" class="pagActive">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#" class="nextPage"><i class="fa fa-angle-right"></i></a>
            </div><!-- ( PAGINATION END ) -->
        </div>
    </div><!-- ( STRIPE END ) -->
<?php endif; ?>
