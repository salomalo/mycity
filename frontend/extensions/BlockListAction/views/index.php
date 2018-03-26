<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $title string */

use yii\helpers\Html;
?>
<div class="block-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content">
    <nav class="left-menu">
        <ul>
            <?php foreach ($models as $model): ?>
            <li <?= (defined('CATEGORYID') && $model->id == CATEGORYID) ? 'class="active"' : '' ?>>
                <?php
                $str = '<span class="icon-menu">' . Html::img('/img/icons/avto.png', ['alt' => '']) . '</span>' . $model->title;
                echo Html::a($str, $this->context->createUrl($model->url));
                ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>