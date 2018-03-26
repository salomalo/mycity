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

<?= Html::a('<i class="fa fa-heart-o"></i> <span data-toggle="I Love It">Добавить в избранное</span>', null, [
    'class' => ['inventor-favorites-btn-toggle', 'heart', ($isMarked ? 'marked' : null)],
    'rel' => 'nofollow',
    'data' => ['listing-id' => $id, 'ajax-url' => $url, 'type' => $type],
]) ?>