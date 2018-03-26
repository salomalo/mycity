<?php

?>

<div class="outer" data-name ="<?=$TitleEmptyOptions?>">
    <select class="form-control">
    <option value="0"><?=$TitleEmptyOptions?></option>
    <?php foreach ($data as $item):?>        
    <option value="<?=$item['id']?>"><?=$item['title']?></option>
    <?php endforeach;?>
    <select>  
</div>

