<?php
use yii\helpers\Url;
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
?>

<div id="filter-4" class="widget widget_filter">
    <div class="widget-inner">

        <h2 class="widgettitle"><?= $title ?> </h2>

        <div class="list-group">

            <?php foreach ($models as $model): ?>
                    <a href="<?= $this->context->createUrl($model->url); ?>" class="list-group-item list-group-item-action my-list <?= (defined('CATEGORYID') && $model->id == CATEGORYID) ? 'active' : '' ?>">
                        <?= $model->title ?>
                    </a>
            <?php endforeach; ?>
        </div>
    </div><!-- /.widget-inner -->
</div>