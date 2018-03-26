<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

$this->title = 'Создать предприятие';
$this->params['breadcrumbs'][] = ['label' => 'Предприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-create">

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,        
    ]) ?>

</div>
