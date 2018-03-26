<?php
/**
 * @var $this \yii\web\View
 * @var $customfieldValue \common\models\ProductCustomfieldValue
 */

use yii\helpers\Html;
?>
<div class="form-group">
    <?= Html::hiddenInput("ProductCustomfieldValue[{$customfieldValue->id}][id]", $customfieldValue->id) ?>
    <?= Html::hiddenInput("ProductCustomfieldValue[{$customfieldValue->id}][idCustomfield]", $customfieldValue->idCustomfield) ?>

    <?= Html::input('text', "ProductCustomfieldValue[{$customfieldValue->id}][value]", $customfieldValue->value, ['class' => 'form-control']) ?>
</div>