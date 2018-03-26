<?php
use yii\helpers\Html;
?>
<ul class="price-list">
    <?php foreach ($models as $item):?>
    <?php
        $date = new DateTime($item->dateCreate);
    ?>
        <li>
            <a href="<?= \Yii::$app->files->getUrl($model, 'price', null, $item->name) ?>"><?= Yii::t('business', 'Download Price')?></a>
            <span class="doc"> 
                <?php 
                    $ex = explode('.', $item->name);
                    echo array_pop($ex);
                ?>
            </span>
            <?php if($isFrontend): ?>
                <span class="update">( <img src="img/icons/watch-black.png" alt="" /> обновлен <?= $date ->format('d.m.Y, H:i') ?>)</span>
            <?php else: ?>
                <?= Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>', 
                        \Yii::$app->urlManager->createUrl(['business/update', 'id' => $model->id, 'actions' => 'deletePrice', 'name' => $item->name]), 
                        [
                            'title' => "Delete",
                            'data-confirm' => "Are you sure you want to delete this item?"
                        ])
                ?>
            <?php endif; ?>
        </li>
    <?php endforeach;?>
</ul>