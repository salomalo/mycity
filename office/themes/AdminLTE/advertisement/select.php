<?php
/* @var $this yii\web\View */
use common\models\Advertisement;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('advertisement', 'Choose block');
$this->params['breadcrumbs'][] = ['label' => Yii::t('advertisement', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$sizes = Advertisement::$sizes;

$original_img = ['width' => 1903, 'height' => 2408];
$showing_img = ['width' => 1000, 'height' => 1265];
$mp = $showing_img['width'] / $original_img['width'];

$coords = [
    Advertisement::POS_SIDE_SQUARE => [1390, 263],
    Advertisement::POS_HEAD_HORIZONTAL => [148, 260],
    Advertisement::POS_SIDE_VERTICAL => [1411, 1787],
];
?>

<!--<img src="/img/block_map.jpg" usemap="#block_map" style="width: 1000px">-->

<img src="/img/block_map_new.png" usemap="#block_map" style="width: 1000px">

<map name="block_map">
    <?php foreach ($coords as $advert => $coord) : ?>

        <?php $coord[] = $coord[0] + $sizes[$advert]['width']; ?>
        <?php $coord[] = $coord[1] + $sizes[$advert]['height']; ?>

        <?php foreach ($coord as &$item) : ?>
            <?php $item *= $mp; ?>
        <?php endforeach; ?>

        <area shape="rect" coords="<?= implode(', ', $coord) ?>"
              href="<?= Url::to(['create', 'pos' => $advert]) ?>"
              alt="<?= Yii::t('advertisement', 'Create block {width}*{height}', $sizes[$advert]) ?>">

    <?php endforeach; ?>
</map>