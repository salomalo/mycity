<?php
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

/**@var \common\models\Business[] $models*/
?>

<div class="block-title"><?=$title?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content popular-predpr">
    <?php foreach ($models as $item): ?>
        <div class="top-predpr-a">
        <?php
        $imageUrl = ($item->image) ? \Yii::$app->files->getUrl($item, 'image', 65) : 'demo/pop_predpriztie.jpg';
        $image = \yii\helpers\Html::img($imageUrl, ['alt' => '']);
        $url = Url::to($item->getRoute());
        echo \yii\helpers\Html::a($image, $url, ['class' => 'pop-predpr', 'title' => $item->title])
        ?>
        </div>
    <?php endforeach;?>
    <div class="clearfix"></div>
    <?php if($idCity):?>
        <i class="fa fa-long-arrow-right"></i>  <a href="<?= Url::to(['business/top']) ?>" class="top-100-link">
                <?= Yii::t('business', 'Top_100_firm_{cityTitle}', ['cityTitle' => ($idCity)? $titleCity : ''])?> 
            </a>
    <?php endif;?>
</div>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

