<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BusinessTemplate */

$this->title = 'Create Business Template';
$this->params['breadcrumbs'][] = ['label' => 'Business Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
