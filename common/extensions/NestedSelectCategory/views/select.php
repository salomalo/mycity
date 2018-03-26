<div class="select">
    <select class="form-control" <?php if($active):?> id="<?=$id?>2" <?php endif;?> name="<?=$name?>" >
        <option value=""><?=$TitleEmptyOptions?></option>
        <?php foreach ($data as $item):?>        
        <option <?php if($select == $item->id):?> selected <?php endif ?>value="<?=$item->id?>"><?=$item->title?></option>
        <?php endforeach;?>
    </select>  
    <input type="hidden" id ="<?=$id?>" length="30" />    
</div>    