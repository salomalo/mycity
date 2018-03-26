<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

$this->title =  $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Предприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="business-update">   
 
   <?=$message; ?>
    <?= $this->render('_form', [
        'model' => $model,
        'adres' => $adres

    ]) ?>

</div>
