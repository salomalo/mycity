<?php
/**@var $this yii\base\View */
/**@var $models \yii\db\ActiveRecord[] */

use yii\helpers\Html;
?>

<div class="block-title"><?= $this->context->title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content">
    <nav class="left-menu">
        <ul>
            <?php foreach ($models as $model) : ?>
                <li <?= (defined('CATEGORYID') and $model['id'] == CATEGORYID ) ? 'class="active"' : '' ?>>
                    <a href="<?= $this->context->createUrl($model['url']); ?>">
                        <span class="icon-menu"><img src="img/icons/avto.png" alt="" /></span><?= $model['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if(!empty($rootCat)) : ?>
            <?= Html::a(Yii::t('business', 'Show_main_headings'), $this->context->createUrl(), ['class' => 'main-cat'])?>
        <?php endif; ?>
    </nav>
</div>