<?php

?>

<div class="multi_select_lexa form-control">
    <ul class="slct">
        <li class= 'placeholder' <?=(count($data)>0)?'style="display:none;"':''; ?> ><?=$placeholder?></li>
        <?php foreach ($data as $item):?>
        <li class= "el_<?=$item->id?>" data-id="<?=$item->id?>"><span class='remove glyphicon glyphicon-remove'></span> <span><?=$item->title?></span></li>        
        <?php endforeach; ?>
    </ul>
    <? if ($search) :?>
        <div class="ul_saerch">
            <input type='text' name='drop_search' class="drop_search form-control" />
            <input type='hidden' name='drop_search_pid' class='drop_search_pid' value="" />
        </div>
    <? endif ?>
    <ul class="drop" data-url="<?=$data_url?>">
    </ul>
    <?=$input?>
</div>