<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CustomfieldCategory */

$this->title = 'Create Customfield Category';
$this->params['breadcrumbs'][] = ['label' => 'Customfield Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customfield-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
