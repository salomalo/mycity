<?php
use yii\helpers\Url;
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
?>
<h2 class="title-list"><?= $this->context->title ?></h2>
<div class="block first">
    <div class="block-title-mini"></div>
    <div class="block-content">
        <div class="left-menu">
            <ul>
                <?php foreach ($models as $model): ?>
                <li>
                    <a href="<?= $this->context->createUrl($model->url); ?>">
                        <span class="icon-menu"><img src="img/icons/avto.png" alt="" /></span><?= $model->title ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>    
            <a href="<?= Yii::$app->urlManager->createUrl('business/index')?>" class="add-akcia"> <span class="add-akcia-pointer"></span><?= Yii::t('business', 'Show_All_Categories')?></a>
        </div>
    </div>
</div>