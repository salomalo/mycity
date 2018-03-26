<?php
/**
 * @var $this \yii\web\View
 * @var $category \common\models\ProductCategory
 * @var $models \common\models\ProductCategory[] | null
 * @var $business \common\models\Business
 */
use yii\helpers\Html;

?>
<?php if ($models) : ?>
    <div class="test-sub-cat" id="item_subcategory_<?= $category->id  ?>" style="display: none;">
        <p style="padding: 10px;"><?= $category->title ?></p>

        <ul>
            <?php foreach ($models as $cat): ?>
                <li>
                    <?= Html::a($cat->title, ['/business/goods', 'alias' => "{$business->id}-{$business->url}", 'urlCategory' => $cat->url]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
