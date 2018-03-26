<?php
/**
 * @var $options array
 * @var $this \yii\web\View
 * @var $pid int
 */
use common\models\Business;
use frontend\extensions\SearchFormNew\SearchFormNew;
use yii\helpers\Html;
use yii\helpers\Url;

$index = $options['index'];
$id_category = $options['id_category'];

?>

<div id="filter-3" class="widget widget_filter">
    <?= SearchFormNew::widget([
        'action' => $options['action'],
        'pid' => $options['pid'],
        'id_category' => $id_category,
        'index' => $index,
    ]) ?>

</div>