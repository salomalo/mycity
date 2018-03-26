<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\models\Ads;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var Ads[] $adses */
$adses = $model->getAds()->orderBy(['isShowOnBusiness' => SORT_DESC])->limit(12)->all();
?>

<?php if ($adses) : ?>
    <div class="listing-detail-section">
        <h2 class="page-header">
            <?= Yii::t('business', 'Goods') ?>
            <?= Html::a(Yii::t('business', 'See all'), ['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => 'goods'], ['class' => 'small-link']) ?>
        </h2>

        <div class="listing-box-archive type-box items-per-row-5">
            <div class="listings-row">
                <div class="b-product-line b-product-line_size_wide js-gallery-container">
                <?php foreach ($adses as $ads) : ?>

                <?= $this->render('_short_ads', ['model' => $ads]) ?>

                <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>