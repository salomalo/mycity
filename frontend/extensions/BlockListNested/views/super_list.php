<?php
/**@var $models \yii\db\ActiveRecord[] */
use yii\helpers\ArrayHelper;

/**@var $this yii\base\View */
/**@var $currentCategoryId integer */
?>


<div id="filter-4" class="widget widget_filter">
    <div class="widget-inner">

        <h2 class="widgettitle"><?= $this->context->title ?> </h2>

        <div class="list-group">

            <?php ArrayHelper::multisort($models, ['title'], [SORT_ASC]); ?>
            <?php foreach ($models as $model): ?>
                <?php $active =  (defined('CATEGORYID') && $model->id == CATEGORYID ) ? 'active' : '' ?>
                    <a href="<?= $this->context->createUrl($model->url); ?>" class="list-group-item list-group-item-action my-list <?= $active?>">
                        <?= $model->title ?>
                    </a>
            <?php endforeach; ?>
            <?php if($rootCat):?>
                <a href="<?= $this->context->createUrl(); ?>" class="list-group-item list-group-item-action my-list"><?= Yii::t('business', 'Show_main_headings')?></a>
            <?php endif;?>
        </div>


    </div><!-- /.widget-inner -->

</div>