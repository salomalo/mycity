<?php

use yii\helpers\Html;

?>

<div class="rating">
Рейтинг :
<span class="rating-val">
    <?=$rating?> 
</span>
<?php echo Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', '#0', [
    'class' => (empty($idUser))? 'btn btn-primary btn-xs' : 'btn btn-primary btn-xs rating-up', 
    'title' => 'up'
    ]);?>

: 

<?php echo Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>', '#0', [
    'class' => (empty($idUser))? 'btn btn-primary btn-xs' : 'btn btn-primary btn-xs rating-down', 
    'title' => 'down']);?>

</div>

<?php if(empty($idUser)):?>
    <?= Html::a('(необходима авторизация)', \Yii::$app->urlManager->createUrl(['site/login']))?>
<?php endif; ?>
