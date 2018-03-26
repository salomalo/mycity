<?php foreach ($attributes as $item): ?>
    <?php if($model->{$item['alias']}):?>
        <p><strong><?= $item->title ?> : </strong> <?= $model->{$item['alias']} ?></p>
    <?php endif;?>
<?php endforeach;?>