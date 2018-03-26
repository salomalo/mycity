<?php

use yii\helpers\Url;

/**@var \yii\web\View $this*/
/**@var \frontend\extensions\BusinessCategory\BusinessCategory $widget*/
/**@var int $count*/
/**@var \common\models\BusinessCategory $model*/
$widget = $this->context;
$url = Url::to(['business/index','pid' => $widget->pid]);
?>

<div class="listing-types-card-container">
    <div class="listing-types-card">
        <div class="listing-types-card-image" style="background-image: url('img/business_cat/<?= $widget->img?>');">
            <a href="<?= $url?>" style="border-color:#<?= $widget->color ?>;">
                <i class="inventor-poi <?= $widget->icon?>" style="background-color: #<?= $widget->color ?>;"></i>
            </a>
        </div><!-- /.listing-types-card-image -->
        <h3 class="listing-types-card-title">
            <a href="<?= $url?>"><?= $model->title?></a>
        </h3><!-- /.listing-types-card-title -->
        <div class="listing-types-card-bottom"><?= $count?> предприятий</div>
    </div><!-- /.listing-types-card -->
</div><!-- /.listing-types-card-container -->
