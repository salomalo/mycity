<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Counter
 */

$this->title = 'Создать счетчик';
$this->params['breadcrumbs'][] = ['label' => 'Counters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="counter-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>