<?php
/**
 * @var $this \yii\web\View
 * @var $model Advertisement
 */
use common\models\Advertisement;
use yii\helpers\Html;

$width = Advertisement::$sizes[$model->position]['width'];
$height = Advertisement::$sizes[$model->position]['height'];
$image = Html::img(Yii::$app->files->getUrl($model, 'image'), ['style' => "width: 100%; height: 100%;"]);
?>
<!--<div style="text-align: center; width: 100%">-->
<!--    --><?php //
//    echo $model->title
//    ?>
<!--</div>-->
<div>
    <?= Html::a($image, $model->url, ['target' => '_blank', 'rel' => 'nofollow']) ?>
</div>
