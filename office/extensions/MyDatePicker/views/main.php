<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Advertisement
 * @var $model_name string
 * @var $inputs array
 * @var $disabled array
 */
use yii\helpers\Url;

?>

<div id="my-datepicker"
     data-fields="<?= implode(',', array_keys($inputs)) ?>"
     data-url="<?= Url::to(['/advertisement/disabled-dates']) ?>"
     data-id="<?= $model->id ?>"
     data-pos="<?= $model->position ?>"
>
    <?php foreach ($inputs as $id => $label) : ?>
        <div class="form-group <?= $model->hasErrors($id) ? 'has-error' : '' ?>">
            <label for="<?= $id ?>"><?= $label ?></label>

            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text"
                       name="<?= "{$model_name}[{$id}]" ?>"
                       class="form-control pull-right"
                       id="<?= $id ?>"
                       placeholder="YYYY-MM-DD"
                       value="<?= $model->{$id} ?>"
                       <?= !$model->{$id} ? 'readonly="true"' : ''?>
                >
            </div>
            <?php if ($model->hasErrors($id)) : ?>
                <div class="help-block"><?= implode('<br>' . PHP_EOL, $model->errors[$id]) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>