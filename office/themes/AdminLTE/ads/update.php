<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 * @var $business common\models\Business
 */



if ($business){
    $this->title = $business->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = Yii::t('ads', 'Update_Ads') . ' ' . $model->title;

    $link = Url::to(['/ads/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = Yii::t('ads', 'Update_Ads') . ' ' . $model->title;
}
?>
<?php if (isset($business->title)) :?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'ads'])?>
<?php endif; ?>

<div class="product-update">
    <h3><?= Yii::t('ads', 'Update_Ads') . ' ' . $model->title ?></h3>

    <?= \common\extensions\AdsForm\AdsForm::widget([
        'model' => $model,
        'idBusiness' => $idBusiness
    ])?>
</div>
