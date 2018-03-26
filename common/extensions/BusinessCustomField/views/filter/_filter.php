<?php
/**
 * @var $this \yii\web\View
 * @var $custom_field \common\models\BusinessCustomField
 * @var $value integer
 */
use yii\helpers\ArrayHelper;

$filter = ArrayHelper::getValue($this->context, 'switchInput');
?>

<div class="cf_filter_spoiler">
    <span class="glyphicon glyphicon-chevron-<?= !empty($value) ? 'up' : 'down' ?>"></span>
    <?= $custom_field->title ?>
</div>

<div class="cf_filter_spoiler_block <?= !empty($value) ?: 'hidden' ?>">
    <div class="form-group">
        <?= $filter($custom_field, $value) ?>
    </div>
</div>