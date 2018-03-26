<?php
/**
 * @var $url string
 * @var $s string
 * @var $pid string
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div>
    <?= Html::beginForm(Url::to([$url]), 'get', ['id' => "find_business"]) ?>
        <div class="col-sm-9 col-xs-9">
            <?= Html::hiddenInput('pid', $pid) ?>
            <?= Html::input('text', 's', $s, ['placeholder' => Yii::t('business', 'Enter_search_phrase')]) ?>
        </div>
        <div class="col-sm-2 col-xs-2 text-center">
            <?= Html::input('submit', null, Yii::t('action', 'Search_btn')) ?>
        </div>
    <?= Html::endForm() ?>
</div>