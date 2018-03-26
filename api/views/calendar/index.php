
<div class="month-nav">
    <span class="prev-month"></span>
    <span data-month="<?= $month ?>" data-year="<?= $year?>" class="month"><?=$monthTitle?> <?=$year?></span>
    <span class="next-month"></span>
</div>

<table cellpadding="0" cellspacing="0" class="calendar">
    <?php 
        $headings = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
    ?>
    <tr class="calendar-row"><td class="calendar-day-head"><?= implode('</td><td class="calendar-day-head">', $headings) ?></td></tr>
    
    <?php
        /* необходимые переменные дней и недель... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        $running_day = $running_day - 1;
        //echo \yii\helpers\BaseVarDumper::dump($running_day, 10, true); die('-running_day');
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        //echo \yii\helpers\BaseVarDumper::dump($days_in_month, 10, true); die('-days_in_month');
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        /* первая строка календаря */
    ?>
    
    <tr class="calendar-row">
        <?php for ($x = 0; $x < $running_day; $x++): ?>
            <td class="calendar-day-np"> </td>
        <?php endfor?>
            
        <?php for ($list_day = 1; $list_day <= $days_in_month; $list_day++): ?>
            <?php
                $dayToTable = $list_day;

                if($time = $this->context->getPost($month, $year, $list_day, $arg)){
                    $dayToTable = '<a href="'.$this->context->createUrl($path, $attribute, $idCategory, $time).'">'.$list_day.'</a>';
                }
            ?>
            <td class="calendar-day">
                <?= $dayToTable?>
            </td>
            <?php if ($running_day == 6):?>
                </tr>
                    <?php if (($day_counter + 1) != $days_in_month):?>
                        <tr class="calendar-row">
                    <?php endif;?>
                            
                    <?php
                        $running_day = -1;
                        $days_in_this_week = 0;
                    ?>
            <?php endif;?>
            
            <?php
                $days_in_this_week++;
                $running_day++;
                $day_counter++;
            ?>
        <?php endfor?>
            
        <?php if($days_in_this_week < 8):?>
            <?php for ($x = 1; $x <= (8 - $days_in_this_week); $x++):?>
                <td class="calendar-day-np"> </td>
            <?php endfor?>
        <?php endif;?>
            
    </tr>
</table>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

