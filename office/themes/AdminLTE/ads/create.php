<?php

use common\extensions\AdsForm\AdsForm;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var $idBusiness integer
 * @var common\models\Ads $model
 * @var $business common\models\Business
 */



if ($business){
    $this->title = Yii::t('ads', 'Add_free_ads');
    $this->params['breadcrumbs'][] = 'Добавление объявления';

    $link = Url::to(['/ads/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
} else {
    $this->title = Yii::t('ads', 'Add_free_ads');
    $this->params['breadcrumbs'][] = 'Добавление объявления';
}

?>
<?php if (isset($business->title)) :?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'ads'])?>
<?php endif; ?>

<div class="product-create">
    <?= LanguageWidget::widget() ?>

    <?= AdsForm::widget([
        'model' => $model,
        'idBusiness' => $idBusiness
    ]) ?>
</div>
