<?php foreach ($attributes as $item): ?>
    <?php if($model->{$item['alias']}):?>
        <li><?= $model->{$item['alias']} ?></li>
    <?php endif;?>
<?php endforeach;?>