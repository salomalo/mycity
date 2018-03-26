<?php
/**
 * @var \common\models\Business $model
 * @var yii\web\View $this
 */
use common\models\BusinessTime;
use yii\helpers\Html;

/**
 * @param $list
 * @param $i
 * @param $attr
 * @return string
 */
function getValue($list, $i, $attr)
{
    return isset($list[$i]->{$attr}) ? $list[$i]->{$attr} : '00:00';
}
function getConfig($name, $value = '00:00') {
    return [
        'name' => $name,
        'value' => $value,
        'containerOptions' => ['class' => 'business_time'],
        'pluginOptions' => ['showMeridian' => false]
    ];
}

/**
 * Подписи к дням недели
 * @var $day array
 */
$day = BusinessTime::$days_week;

$list = [];
foreach ($model->times as $item) {
    $list[$item->weekDay] = $item;
}

$all = true;
foreach ($list as $item){
    if (empty($list[1]) or ((($list[1]->start != $item->start) or ($list[1]->end != $item->end)) and ($item->weekDay < 6))) {
        $all = false;
    }
}

?>
<div class="row">
    <div class="col-lg-10">
        <?= $this->render('_time_input', [
            'title' => ($all ? 'Понедельник - Пятница' : $day[1]),
            'labelClass' => 'days',
            'i' => 1,
            'widgetValues' => [
                getValue($list, 1, 'start'),
                getValue($list, 1, 'end'),
                getValue($list, 1, 'break_start'),
                getValue($list, 1, 'break_end')
            ]
        ])?>
    </div>
    <div class="col-lg-10">
        <label class="control-label addDays">
            <?= Html::a('Показать расписание подневно', '#0') ?>
        </label>
        <div class="workDays" style="display: none;">
            <?php for ($i = 2; $i <= 5; $i++): ?>
                <?= $this->render('_time_input', [
                    'title' => $day[$i],
                    'labelClass' => '',
                    'i' => $i,
                    'widgetValues' => [
                        getValue($list, $i, 'start'),
                        getValue($list, $i, 'end'),
                        getValue($list, $i, 'break_start'),
                        getValue($list, $i, 'break_end')
                    ]
                ])?>
            <?php endfor; ?>
        </div>
    </div>
    <div class="col-lg-10">
        <label class="control-label weeckend_addDays">
            <?= Html::a('Показать расписание на выходные дни', '#0') ?>
        </label>
        <div class="weeckend" style="display: none;">
            <?php for ($i = 6; $i <= 7; $i++): ?>
                <?= $this->render('_time_input', [
                    'title' => $day[$i],
                    'labelClass' => '',
                    'i' => $i,
                    'widgetValues' => [
                        getValue($list, $i, 'start'),
                        getValue($list, $i, 'end'),
                        getValue($list, $i, 'break_start'),
                        getValue($list, $i, 'break_end')
                    ]
                ]) ?>
            <?php endfor; ?>
        </div>
    </div>
</div>