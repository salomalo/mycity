<?php
/**
 * @var $this \yii\web\View
 * @var $productCategory \common\models\ProductCategory[]
 * @var $business \common\models\Business
 */

use frontend\themes\kuteshop\AppAssets;
use yii\helpers\Url;

$bundle = AppAssets::register($this);
$alias = "{$business->id}-{$business->url}";
?>
<div class="block-nav-categori">
    <div class="block-title">
        <span>Категории</span>
    </div>
    <div class="block-content">
        <div class="clearfix"><span data-action="close-cat" class="close-cate"><span>Categories</span></span></div>
        <ul class="ui-categori">
            <?php foreach ($productCategory as $category) : ?>
                <li>
                    <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => $category->url])?>">
                        <span class="icon"><img src="<?= $bundle->baseUrl . '/images/icon/index1/nav-cat5.png' ?>" alt="nav-cat"></span>
                        <?= $category->title ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

<!--        <div class="view-all-categori">-->
<!--            <a  class="open-cate btn-view-all">Все категории</a>-->
<!--        </div>-->
    </div>

</div>