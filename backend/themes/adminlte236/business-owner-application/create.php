<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BusinessOwnerApplication */

$this->title = 'Create Business Owner Application';
$this->params['breadcrumbs'][] = ['label' => 'Business Owner Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-owner-application-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>
</div>