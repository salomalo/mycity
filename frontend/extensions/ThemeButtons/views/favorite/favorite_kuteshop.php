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

<?= Html::a('<span>wishlist</span>', null, [
    'class' => ['btn', 'btn-wishlist', 'btn-add-to-wish-list', ($isMarked ? 'marked' : null)],
    'rel' => 'nofollow',
    'data' => ['listing-id' => $id, 'ajax-url' => $url, 'type' => $type],
]) ?>
