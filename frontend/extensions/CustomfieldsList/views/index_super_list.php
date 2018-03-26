<h4 class="char-podtitle"><!-- Характеристики экрана:--></h4>
<ul class="chars">
    <li><strong class="key"><?= Yii::t('product', 'Manufacturer')?> </strong> <span class="value"><?= ($model->company)? $model->company->title : '' ?></span></li>
    <?php if(!empty($model->tovar)):?>
        <li><strong class="key"><?= Yii::t('product', 'pattern')?> </strong> <span class="value"><?= $model->tovar->model ?></span></li>
    <?php endif; ?>

    <?php $categoryTitle = '';?>
    <?php foreach ($attributes as $item): ?>
        <?php if($model->{$item['alias']}):?>
            <?php if($item->categoryCustomfield):?>
                <?php if($categoryTitle != $item->categoryCustomfield->title): ?>
                    <li><strong class="key"><?= $item->categoryCustomfield->title ?> </strong> <span class="value"><?php $categoryTitle = $item->categoryCustomfield->title; ?></span></li>
                <?php endif;?>
            <?php else:?>
                <!--<li></li>-->
            <?php endif;?>
            <li><strong class="key"><?= $model->getTitleCustomfield($item['alias']) ?></strong> <span class="value"><?= $model->{$item['alias']} ?></span></li>
        <?php endif;?>
    <?php endforeach;?>
</ul>