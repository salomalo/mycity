<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var array $address
 */

$this->title = Yii::t('business', 'Add_Businesses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="business-create">
    <?= $this->render('_form', ['model' => $model, 'address' => $address]) ?>
</div>