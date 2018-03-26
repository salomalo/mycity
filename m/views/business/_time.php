<?php

use kartik\widgets\TimePicker;
use common\models\BusinessTime;

$day = BusinessTime::$days_week;

if (empty($model->times)): ?>

    <div class="row form-group">
        <div class="col-sm-12 col-xs-12">
            <label class="control-label days">Понедельник - Пятница</label>
        </div>
        <div class="col-sm-12 col-xs-12">
            
            <div class="col-sm-6 col-xs-12">
                <div>c</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'start_time[1]',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'options' => ['type' => 'time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div>до</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'end_time[1]',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'options' => ['type' => 'time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?></div>
            </div>

        </div>
</div>

<div class="form-group business_time-full-week">
    <label class="control-label">Расписание на всю неделю:</label>
    <input type="checkbox" class="full_week" name="business_time_full_week">
</div>


<?php for ($i = 6; $i < 8; $i++): ?>
<div class="row form-group">                  
        <div class="col-sm-12 col-xs-12">
             <label class="control-label"><?= $day[$i] ?></label>
        </div>
        <div class="col-sm-12 col-xs-12">     
            <div class="col-sm-6 col-xs-12">
                <div>c</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'start_time[' . $i . ']',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'options' => ['type' => 'time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div>до</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'end_time[' . $i . ']',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'options' => ['type' => 'time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?></div>
            </div>
        </div>
</div>
<?php endfor; ?>

<?php else: ?>

<?php
    $list = [];
    foreach ($model->times as $item) {
        $list[$item->weekDay] = $item;
    }

    $all = true;
    foreach ($list as $item){
        if(($list[1]->start != $item->start || $list[1]->end != $item->end) && $item->weekDay < 8){
            $all = false;
        }
    }
    
    if(count($list) == 1){
        $all = false;
    }
?>    


<div class="row form-group">
        <div class="col-sm-12 col-xs-12">
            <label class="control-label days">Понедельник - Пятница</label>
        </div>
        <div class="col-sm-12 col-xs-12">
            
            <div class="col-sm-6 col-xs-12">
                <div>c</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'start_time[1]',
                        'value' => (isset($list[1]->start)) ? $list[1]->start : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div>до</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'end_time[1]',
                        'value' => (isset($list[1]->end)) ? $list[1]->end : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?></div>
            </div>

        </div>
</div>

<div class="form-group business_time-full-week">
    <label class="control-label">Расписание на всю неделю:</label>
    <input type="checkbox" class="full_week" name="business_time_full_week" <?= ($all)? 'checked="checked"' : ''?> >
</div>

<?php for ($i = 6; $i < 8; $i++): ?>
<div class="row form-group">                  
        <div class="col-sm-12 col-xs-12">
             <label class="control-label"><?= $day[$i] ?></label>
        </div>
        <div class="col-sm-12 col-xs-12">     
            <div class="col-sm-6 col-xs-12">
                <div>c</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'start_time[' . $i . ']',
                        'value' => (isset($list[$i]->start)) ? $list[$i]->start : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div>до</div>  <div><?=
                    TimePicker::widget([
                        'name' => 'end_time[' . $i . ']',
                        'value' => (isset($list[$i]->end)) ? $list[$i]->end : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]);
                    ?></div>
            </div>
        </div>
</div>
<?php endfor; ?>

<?php endif; ?>