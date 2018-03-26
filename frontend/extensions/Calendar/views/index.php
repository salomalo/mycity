<?php
/**
 * @var $action string
 * @var $month string
 * @var $day string
 * @var $year string
 * @var $sortTime string
 */
?>
<div class="block-title"><?= $title ?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content" id="calendar">
    <form meethod="get" id="filter_calendar" action="<?= $action ?>">
        <input type="hidden" name="time" value="">
        <?php if ($sortTime): ?>
            <input type="hidden" name="sort" value="<?= $sortTime ?>">
        <?php endif; ?>
    </form> 
<div class="month-nav">
    <span class="prev-month"></span>
    <span class="month"></span>
    <span class="next-month"></span>
</div>    
    <table id="calendar2" data-day = "<?=$day?>" data-month = "<?=$month?>" data-year = "<?=$year?>">
  <thead>    
    <tr class="calendar-row">
        <td class="calendar-day-head">Пн</td>
        <td class="calendar-day-head">Вт</td>
        <td class="calendar-day-head">Ср</td>
        <td class="calendar-day-head">Чт</td>
        <td class="calendar-day-head">Пт</td>
        <td class="calendar-day-head">Сб</td>
        <td class="calendar-day-head">Вс</td>
    </tr>        
  <tbody>
</table>
</div>