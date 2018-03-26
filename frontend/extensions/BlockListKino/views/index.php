<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
?>
<div class="block-title"><?= $this->context->title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content">
    <nav class="left-menu">
        <ul>
            <?php foreach ($models as $model): ?>
            <li 
                <?php 
                    if(defined('CATEGORYID') && $model->id == CATEGORYID ){
                        echo 'class="active"';
                    }
                ?>
            >
                    <a href="<?= $this->context->createUrl($model->url); ?>">
                        <span class="icon-menu"><img src="img/icons/avto.png" alt="" /></span><?= $model->title ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>