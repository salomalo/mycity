<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Counter
 */

$this->title = "Изменить счетчик: {$model->title}";
$this->params['breadcrumbs'][] = ['label' => 'Counters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="counter-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>