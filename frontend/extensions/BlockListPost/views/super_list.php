<?php
/**@var $models \yii\db\ActiveRecord[] */

use yii\helpers\Html;
?>

<div id="filter-4" class="widget widget_filter">
    <div class="widget-inner">

        <h2 class="widgettitle"><?= $this->context->title ?> </h2>

        <div class="list-group">
            <?php foreach ($models as $model): ?>
                    <?php
                    if(defined('CATEGORYID') && $model->id == CATEGORYID ){
                        $active = 'active';
                    } else {
                        $active = '';
                    }
                    ?>

                <a href="<?= $this->context->createUrl($model->url); ?>" class="list-group-item list-group-item-action my-list <?= $active?>">
                    <?= $model->title ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div><!-- /.widget-inner -->
</div>