<?php
/**
 * @var $this \yii\web\View
 * @var $title string
 * @var $labelClass string
 * @var $widgetValues array
 * @var $i integer
 */
use kartik\widgets\TimePicker;
?>

<div class="col-lg-10">
    <label class="control-label <?= $labelClass ?>"><?= $title ?></label>
    <div class="time_line">
        <div>c</div><div><?= TimePicker::widget(getConfig("start_time[$i]", $widgetValues[0])); ?></div>
        <div>до</div><div><?= TimePicker::widget(getConfig("end_time[$i]", $widgetValues[1])); ?></div>
        <div>Перерыв</div>
        <div>с</div><div><?= TimePicker::widget(getConfig("break_start[$i]", $widgetValues[2])); ?></div>
        <div>до</div><div><?= TimePicker::widget(getConfig("break_end[$i]", $widgetValues[3])); ?></div>
    </div>
</div>