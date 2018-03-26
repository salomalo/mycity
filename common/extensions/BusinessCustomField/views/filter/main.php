<?php
/**
 * @var $this \yii\web\View
 * @var $custom_fields \common\models\BusinessCustomField[]
 * @var $attributes array
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (!empty($custom_fields)) : ?>
    <div class="cf_filter_block">
        <?= Html::beginForm(Url::current(), 'get', ['id' => 'cf_filter']) ?>

            <?php foreach ($custom_fields as $custom_field) : ?>
                <div class="cf_filter_item">
                    <?= $this->render('_filter', [
                        'custom_field' => $custom_field,
                        'value' => empty($attributes) ? null : ArrayHelper::getValue($attributes, $custom_field->id, null)
                    ]) ?>
                </div>
            <?php endforeach; ?>

        <div class="form-group">
            <?= Html::input('submit', null, Yii::t('widgets', 'accept'), ['style' => 'width: 180px']) ?>
        </div>
        <?= Html::resetButton('Сбросить', ['class' => 'form-control btn btn-danger cf_filter_reset']) ?>

        <?= Html::endForm(); ?>
    </div>
<?php endif; ?>