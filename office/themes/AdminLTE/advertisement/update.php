<?php
/**
 * @var $model \common\models\Advertisement
 * @var $this yii\web\View
 */

$this->title = Yii::t('advertisement', 'Update') . " $model->title";
$this->params['breadcrumbs'][] = ['label' => Yii::t('advertisement', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="advertisement-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>