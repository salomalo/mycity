<?php
/**
 * @var $this \yii\web\View
 * @var $title string
 * @var $icon string
 * @var $url string|array
 * @var $counter integer
 * @var $counter_id string
 */

use yii\helpers\Html;

$icon = Html::tag('i', '', ['class' => (!empty($icon) ? $icon : 'fa fa-comment')]);
$title = Html::tag('span', $title);
$counter = empty($counter) ? '' : $counter;
$counter_id = empty($counter_id) ? null : $counter_id;
$counter = Html::tag('small', $counter, ['class' => 'badge pull-right bg-yellow', 'id' => $counter_id]);
?>

<?= Html::tag('li', Html::a("$icon $title $counter", $url)) ?>