<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use common\models\Product;
use frontend\extensions\MyLinkPager\MyLinkPager;
?>

<?php $class_modal =(Yii::$app->user->isGuest)?" message_login_auth":" "; ?>

<?php
    $models = $dataProvider->getModels();
    $attributes = Product::getCustomfieldsModel($models[0]->idCategory);
?>


<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="200px"></th>
            <?php foreach ($models as $item):?>
                <th><?= $item->title?></th>
            <?php endforeach;?>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th></th>
            <?php foreach ($models as $item):?>
                <th>
                    <?= ($item->image)? Html::a(
                        Html::img(Yii::$app->files->getUrl($item, 'image', 100)), 
                        Url::to(['view','alias'=>$item->url.'']), []) : '' 
                    ?>
                </th>
            <?php endforeach;?>
        </tr>
        
        <?php $categoryTitle = '';?>
        <?php foreach ($attributes as $item):?>
            <?php if($item->categoryCustomfield): ?>
                <?php if($categoryTitle != $item->categoryCustomfield->title): ?>
                                <tr>   
                                    <td><?= $item->categoryCustomfield->title ?></td>
                                    <?php $categoryTitle = $item->categoryCustomfield->title; ?>
                                    <?php foreach ($models as $m):?>
                                        <td></td>
                                    <?php endforeach;?>
                                </tr>
                                
                <?php endif;?>
            <?php endif;?>
            <tr>
                <td><?= $item->title?></td>
                    <?php foreach ($models as $model):?>
                        <td><?= ($model->{$item->alias})? $model->{$item->alias} : '' ?></td>
                    <?php endforeach;?>
            </tr>
        <?php endforeach;?>
        
        <tr>
            <th><?= Yii::t('ads', 'Price')?></th>
            <?php foreach ($models as $item):?>
                <th><?= ($item->price)? $item->price : '' ?></th>
            <?php endforeach;?>
        </tr>
    </tbody>
</table>

<?php echo MyLinkPager::widget([
    'pagination' => $pages,
]);?>