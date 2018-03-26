<?php
/**
 * @var $item
 * @var $pref
 * @var $view
 */
use yii\helpers\Html;
?>
<style>
    .red {
        color: red;
    }
</style>
<?php
$views = 'Просм: <span class="red">' . $view .'</span>';
?>
<div style="<?=$style?>" >
    <?=$pref?>
    <?=$item['title']?>
    <?= (!empty($item['countBusiness']))? $item['countBusiness'] : ''?>
    <?= $views ?>
    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                Yii::$app->urlManager->createUrl(['business-category/update', 'id' => $item['id']]),
                [
                    'title' => 'Update' ,
                    'data-pjax' => '0'
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
                Yii::$app->urlManager->createUrl(['business-category/delete', 'id' => $item['id']]),
                [
                    'title' => 'Delete' ,
                    'data-confirm' => 'Are you sure you want to delete this item?',
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>',
                Yii::$app->urlManager->createUrl(['business-category/add', 'id' => $item['id']]),
                [
                    'title' => 'Добавить вложенную' ,
                    'data-pjax' => '0'
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>',
                Yii::$app->urlManager->createUrl(['business-category/up', 'id' => $item['id']]),
                [
                    'title' => 'Переместить вверх' ,
                    'data-pjax' => '0'
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-down"></span>',
                Yii::$app->urlManager->createUrl(['business-category/down', 'id' => $item['id']]),
                [
                    'title' => 'Переместить вниз' ,
                    'data-pjax' => '0'
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>',
                Yii::$app->urlManager->createUrl(['business-category/left', 'id' => $item['id']]),
                [
                    'title' => 'Переместить влево' ,
                    'data-pjax' => '0'
                ]
            )
    ?>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>',
                Yii::$app->urlManager->createUrl(['business-category/right', 'id' => $item['id']]),
                [
                    'title' => 'Переместить вправо' ,
                    'data-pjax' => '0'
                ]
            )
    ?>

</div>