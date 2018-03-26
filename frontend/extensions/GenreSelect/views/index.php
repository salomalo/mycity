<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
use yii\helpers\Html;
?>
<div class="block-title">Жанры кино:<i class="fa fa-long-arrow-down"></i></div>
<div class="block-content">
    <nav class="left-menu">
        <ul>
            <?php foreach ($models as $model): ?>
            <li <?php if($active === $model->id) echo ' class="active"'; ?> >
                <a href="<?= $this->context->createUrl($model->url); ?>">
                    <span class="icon-menu"><?= Html::img('img/icons/avto.png')?></span><?= $model->title ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>