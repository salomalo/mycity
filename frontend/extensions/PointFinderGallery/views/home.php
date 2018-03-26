<?php
use frontend\extensions\PointFinderGallery\PointFinderGallery;
/**@var $data*/
/**@var $this \yii\web\View*/
?>

<div id="pricing-2" class="widget widget_pricing">
    <div class="widget-inner widget-border-top widget-pt widget-pb">
        <h2 class="widgettitle"><?= $this->context->title?></h2>
        <div class="description"></div><!-- /.description -->
        <?= PointFinderGallery::widget(['data' => $data]) ?>
    </div><!-- /.widget-inner -->
</div>