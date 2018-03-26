<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

$this->title = Yii::t('business', 'Create_busi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'selectCity' => $selectCity,
    ]) ?>

</div>
