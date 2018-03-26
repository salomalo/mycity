<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WidgetCityPublic */

$this->title = 'Create Widget City Public';
$this->params['breadcrumbs'][] = ['label' => 'Vk Widget City Publics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-city-public-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
