<?php
use kartik\widgets\TimePicker;
use common\models\BusinessTime;

$day = BusinessTime::$days_week;

if (empty($model->times)) : ?>

    <div class="row">
        <div class="col-lg-10">
            <label class="control-label days"><?= Yii::t('business', 'Mo-Fr')?></label>
            <div class="time_line">
                <div><?= Yii::t('business', 'from')?></div>
                <div>
                    <?= TimePicker::widget([
                        'name' => 'start_time[1]',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false],
                    ]) ?>
                </div>
                <div>
                    <?= Yii::t('business', 'to') ?></div>
                <div>
                    <?= TimePicker::widget([
                        'name' => 'end_time[1]',
                        'value' => '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false],
                    ]) ?>
                </div>
            </div>

        </div>


        <div class="col-lg-10">
            <label class="control-label addDays"><a href="#0"><?= Yii::t('business', 'Add_Schedule_podnevno')?></a></label>

            <div class="workDays" style="display: none;">

                <?php for ($i = 2; $i < 6; $i++): ?>

                    <label class="control-label"><?= $day[$i] ?></label>
                    <div class="time_line">

                        <div><?= Yii::t('business', 'from')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => "start_time[$i]",
                                'value' => '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                        <div><?= Yii::t('business', 'to')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => "end_time[$i]",
                                'value' => '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                    </div>

                <?php endfor; ?>

            </div>
        </div>


        <div class="col-lg-10">
            <label class="control-label weeckend_addDays"><a href="#0"><?= Yii::t('business', 'Add_a_schedule_for_the_weekend')?></a></label>

            <div class="weeckend" style="display: none;">

                <?php for ($i = 6; $i < 8; $i++): ?>

                    <label class="control-label"><?= $day[$i] ?></label>
                    <div class="time_line">

                        <div><?= Yii::t('business', 'from')?></div>
                        <div><?= TimePicker::widget([
                                'name' => "start_time[$i]",
                                'value' => '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                        <div><?= Yii::t('business', 'to')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => "end_time[$i]",
                                'value' => '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                    </div>

                <?php endfor; ?>



            </div>
        </div>

    </div>
<?php else: ?>
    <div class="row">

    <?php
    $list = [];
    foreach ($model->times as $item) {
        $list[$item->weekDay] = $item;
    }
    
    $all = true;
    foreach ($list as $item){
        if(($list[1]->start != $item->start || $list[1]->end != $item->end) && $item->weekDay < 6){
            $all = false;
        }
    }
    ?>      
        <div class="col-lg-10">
            <label class="control-label days"><?= ($all) ? Yii::t('business', 'Mo-Fr') : $day[1] ?></label>
            <div class="time_line">

                <div><?= Yii::t('business', 'from')?></div>
                <div>
                    <?= TimePicker::widget([
                        'name' => 'start_time[1]',
                        'value' => (isset($list[1]->start)) ? $list[1]->start : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]); ?>
                </div>
                <div><?= Yii::t('business', 'to')?></div>
                <div>
                    <?= TimePicker::widget([
                        'name' => 'end_time[1]',
                        'value' => (isset($list[1]->end)) ? $list[1]->end : '00:00',
                        'containerOptions' => ['class' => 'business_time'],
                        'pluginOptions' => ['showMeridian' => false]
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <label class="control-label addDays"><a href="#0"><?= Yii::t('business', 'Add_Schedule_podnevno')?></a></label>

            <div class="workDays" style="display: none;">

                <?php for ($i = 2; $i < 6; $i++): ?>

                    <label class="control-label"><?= $day[$i] ?></label>
                    <div class="time_line">

                        <div><?= Yii::t('business', 'from')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => 'start_time[' . $i . ']',
                                'value' => (isset($list[$i]->start)) ? $list[$i]->start : '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                        <div><?= Yii::t('business', 'to')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => 'end_time[' . $i . ']',
                                'value' => (isset($list[$i]->end)) ? $list[$i]->end : '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                    </div>

                <?php endfor; ?>

            </div>
        </div>



        <div class="col-lg-10">
            <label class="control-label weeckend_addDays"><a href="#0"><?= Yii::t('business', 'Add_a_schedule_for_the_weekend')?></a></label>
            <div class="weeckend" style="display: none;">
                <?php for ($i = 6; $i < 8; $i++): ?>

                    <label class="control-label"><?= $day[$i] ?></label>
                    <div class="time_line">

                        <div><?= Yii::t('business', 'from')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => 'start_time[' . $i . ']',
                                'value' => (isset($list[$i]->start)) ? $list[$i]->start : '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                        <div><?= Yii::t('business', 'to')?></div>
                        <div>
                            <?= TimePicker::widget([
                                'name' => 'end_time[' . $i . ']',
                                'value' => (isset($list[$i]->end)) ? $list[$i]->end : '00:00',
                                'containerOptions' => ['class' => 'business_time'],
                                'pluginOptions' => ['showMeridian' => false]
                            ]); ?>
                        </div>
                    </div>

                <?php endfor; ?>
            </div>
        </div>
    </div>

<?php endif; ?>