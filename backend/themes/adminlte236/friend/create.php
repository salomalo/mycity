<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Friend */

$this->title = 'Create Friend';
$this->params['breadcrumbs'][] = ['label' => 'Friends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="friend-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
