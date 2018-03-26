<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
?>


<div id="filter-4" class="widget widget_filter">
    <div class="widget-inner">

        <h2 class="widgettitle"><?= $this->context->title ?> </h2>

<div class="list-group">
    <?php foreach ($models as $model): ?>
        <?php
        if($active === $model->id){
            $class = 'active';
        } else {
            $class = '';
        }
        ?>

        <a href="<?= $this->context->createUrl($model->url); ?>" class="list-group-item list-group-item-action my-list <?= $class?>">
            <?= $model->title ?>
        </a>
    <?php endforeach; ?>
</div>
</div><!-- /.widget-inner -->
</div>