<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var array $address
 */

use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="business-update">
    <?= $this->render('top_block', ['id' => $model->id, 'active' => 'update'])?>
 
    <?= $message; ?>

    <?= $this->render('_form', ['model' => $model, 'address' => $address]) ?>
</div>