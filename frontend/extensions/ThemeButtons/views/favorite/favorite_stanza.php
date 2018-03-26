<?php
/**
 * @var $this \yii\web\View
 * @var $url string
 * @var $id string
 * @var $type integer
 * @var $isMarked boolean
 */
use yii\helpers\Html;

?>

<?= Html::a('<i class="fa fa-heart-o"></i>', null, [
    'class' => ['heart', 'hovicon', 'btn-add-to-wish-list', ($isMarked ? 'marked' : null)],
    'rel' => 'nofollow',
    'data' => ['listing-id' => $id, 'ajax-url' => $url, 'type' => $type],
]) ?>
