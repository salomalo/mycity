<?php
/**
 * @var $this yii\web\View
 * @var $model \common\models\Advertisement
 */

$this->title = Yii::t('advertisement', 'Create') . " '$model->positionLabel'";
$this->params['breadcrumbs'][] = ['label' => Yii::t('advertisement', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="advertisement-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>