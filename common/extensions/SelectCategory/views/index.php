<?php

?>

<div class="outer">
    <select class="form-control" name="selectcategory">
    <option value="0"><?=$TitleEmptyOptions?></option>
    <?php foreach ($data as $item):?>        
    <option value="<?=$item['id']?>"><?=$item['title']?></option>
    <?php endforeach;?>
    <select>  
</div>

