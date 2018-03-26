<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $business \common\models\Business*/
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**@var $this yii\base\View */
$activeRootCat = 'super-list-active';
$alias = "{$business->id}-{$business->url}";
?>


<div id="filter-4" class="widget widget_filter">
    <div class="widget-inner">

        <h2 class="widgettitle"><?= Yii::t('ads', 'Categories_ad')?> </h2>

        <div class="list-group">

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
                <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => $model->url])?>" class="list-group-item list-group-item-action my-list <?= $active?>">
                    <?= $model->title ?>
                </a>
            <?php endforeach; ?>
            <?php if($rootCat):?>
                <a href="<?= Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => 'goods'])?>" class="list-group-item list-group-item-action my-list <?= $activeRootCat ?>"><?= Yii::t('business', 'Show_main_headings')?></a>
            <?php endif;?>
        </div>


    </div><!-- /.widget-inner -->

</div>