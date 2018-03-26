<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use yii\helpers\Html;

$categories = $model->productCategory();
?>

<?php if ($categories) : ?>
    <div class="listing-detail-section" id="listing-detail-section-property-amenities">
        <h2 class="page-header"><?= Yii::t('business', 'Product categories') ?></h2>

        <div class="listing-detail-property-amenities">
            <ul>
                <?php foreach ($categories as $category) : ?>
                    <li class="yes"><?= Html::a($category->title, ['/ads/index', 'pid' => $category->url]) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>