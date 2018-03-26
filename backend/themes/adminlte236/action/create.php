<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Action */

$this->title = 'Create Action';
$this->params['breadcrumbs'][] = ['label' => 'Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
