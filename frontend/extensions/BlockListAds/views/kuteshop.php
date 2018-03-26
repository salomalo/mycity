<?php
/**@var $models \common\models\ProductCategory[] */
/**@var $business \common\models\Business*/
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**@var $this yii\base\View */
$activeRootCat = 'super-list-active';
$alias = "{$business->id}-{$business->url}";
?>

<!-- Block  bestseller products-->
<div class="block-sidebar block-sidebar-categorie">
    <div class="block-title">
        <strong>Категории продуктов</strong>
    </div>
    <div class="block-content">
        <ul class="items">
            <?php ArrayHelper::multisort($models, ['title'], [SORT_ASC]); ?>
            <?php foreach ($models as $model): ?>
                <?php
                if ($idCategory && $model->id == (int)$idCategory){
                    $active = 'active';
                    $activeRootCat = '';
                } else {
                    $active = '';
                }
                ?>
                <li>
                    <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => $model->url])?>" class="<?= $active?>"><?= $model->title ?></a>
                </li>
            <?php endforeach; ?>
            <?php if($rootCat):?>
                <li>
                    <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => 'goods'])?>" class="<?= $activeRootCat?>">Показать основные категории</a>
                </li>
            <?php endif;?>
        </ul>
    </div>
</div><!-- Block  bestseller products-->