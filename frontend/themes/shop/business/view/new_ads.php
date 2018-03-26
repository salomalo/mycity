<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $listCategory array
 */

use common\models\Ads;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var Ads[] $adses */
$adses = $model->getAds()->orderBy(['views' => SORT_DESC])->limit(9)->all();
?>
<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<?php if ($adses) : ?>
<div class="listing-detail-section">
    <h2 class="page-header"  style="margin-top: 40px;">
        <?= Yii::t('business', 'Популярные товары') ?>
        <?= Html::a(Yii::t('business', 'See all'), ['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => 'goods'], ['class' => 'small-link']) ?>
    </h2>
    <div class="b-product-line b-product-line_size_wide js-gallery-container">
        <?php foreach ($adses as $ads) : ?>
            <?= $this->render('_short_ads', ['model' => $ads]) ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
