<h4 class="char-podtitle"><!-- Характеристики экрана:--></h4>
    <ul class="chars">
        <li>
            <div><?= Yii::t('product', 'Manufacturer')?></div>
            <div><?= ($model->company)? $model->company->title : '' ?></div>
        </li>
        <?php if(!empty($model->tovar)):?>
        <li>
            <div><?= Yii::t('product', 'pattern')?></div>
            <div><?= $model->tovar->model ?></div>
        </li>
        <?php endif; ?>
        
        <?php $categoryTitle = '';?>
        <?php foreach ($attributes as $item): ?>
            <?php if($model->{$item['alias']}):?>
                <?php if($item->categoryCustomfield):?>
                        <?php if($categoryTitle != $item->categoryCustomfield->title): ?>
                            <li>   
                                <div><?= $item->categoryCustomfield->title ?></div>
                                <div><?php $categoryTitle = $item->categoryCustomfield->title; ?></div>
                            </li>
                        <?php endif;?>
                <?php else:?>
                <!--<li></li>-->
                <?php endif;?>
                <li>
                    <div><?= $model->getTitleCustomfield($item['alias']) ?></div>
                    <div><?= $model->{$item['alias']} ?></div>
                </li>
            <?php endif;?>
        <?php endforeach;?>
    </ul> 