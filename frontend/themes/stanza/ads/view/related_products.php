<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use common\models\Ads;

/** @var Ads[] $ads */
$adsModels = $model->getAds()->orderBy(['_id' => SORT_DESC])->limit(4)->all();
?>

<?php if ($adsModels) : ?>
<div class="stripe">
    <div class="container">
        <h3 class="dashStyle">ПОХОЖИЕ ТОВАРЫ</h3>
        <div class="productsRow row">
            <?php foreach ($adsModels as $ads) : ?>
                <?= $this->render('_short_ads', ['model' => $ads]) ?>
            <?php endforeach; ?>
        </div><!-- ( PRODUCTS ROW END ) -->
    </div>
</div><!-- ( STRIPE END ) -->
<?php endif; ?>