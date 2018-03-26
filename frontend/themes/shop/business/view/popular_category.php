<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use common\models\Ads;
use common\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var  ProductCategory[] $categories */
$allRootCategory = ProductCategory::find()->roots()->all();
/** @var  ProductCategory[] $adsCategory */
$notEmptyCategory = [];
foreach ($allRootCategory as $cat){
    $childCat = ArrayHelper::getColumn($cat->children()->all(), 'id');
    if (!empty($childCat)) {
        $whereInCategory = [
            'or',
            ['idCategory' => $cat->id],
            ['idCategory' => $childCat]
        ];
        $ad = Ads::find()
            ->where(['idBusiness' => $model->id])
            ->andWhere($whereInCategory)
            ->count();
        if ($ad){
            $notEmptyCategory[] = $cat;
        }
    }
}

$sortFunction = function ($x, $y) {
    return strcasecmp($x->title, $y->title);
};

usort($notEmptyCategory, $sortFunction);
?>

<div class="listing-detail-section">
    <div class="listing-detail-section">
        <h2 class="page-header" style="margin-top: 40px;">
            Популярные категории
        </h2>
        <div class="b-product-line b-product-line_size_wide js-gallery-container">
            <?php foreach ($notEmptyCategory as $category) : ?>
                <div itemscope="itemscope" itemtype="http://schema.org/Product" class="
                b-product-gallery b-hovered  qa-product-block
                js-product-line js-tracking
                js-rtb-partner" style="height: 260px;margin-left: 20px;">
                    <div class="b-product-gallery__content">

                        <div class="b-product-gallery__holder">
                            <a href="<?= Url::to(['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => $category->url])?>" itemprop="url" class="b-image-holder js-favourites-popup">
                                <img alt="123" class="b-image-holder__img"
                                     src="<?= Yii::$app->files->getUrl($category, 'image') ?>">
                            </a>
                        </div>

                        <div class="b-product-gallery__content-spacer" style="min-height: 50px;">
                            <div class="h-mt-15">

                                <div class="listing-small-location">
                                    <a href="<?= Url::to(['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => $category->url])?>">
                                        <?= $category->title ?>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
