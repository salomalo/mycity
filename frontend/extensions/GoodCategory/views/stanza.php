<?php
/**
 * @var $this \yii\web\View
 * @var $adsCategories array
 * @var $model \common\models\Business
 */
use common\models\Ads;
use yii\helpers\Url;

?>
<div class="stripe">
    <div class="container">
        <h3 class="dashStyle">Просмотр по категориям</h3>
        <div class="categoryRow row">
            <ul id="catCarousel" class="clearfix">
                <?php foreach ($adsCategories as $category) : ?>
                    <li>
                        <div class="col-xs-12">
                            <div class="categoryBox">
                                <a href="<?= Url::to(['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => $category->url])?>">
                                    <div class="categoryImage" style="height:379px;">
                                        <img src="<?= Yii::$app->files->getUrl($category, 'image') ?>" width="263" height="379" alt="">
                                        <div class="title">
                                            <?= $category->title ?>
                                            <span><?= Ads::find()->where(['idBusiness' => $model->id, 'idCategory' => $category->id])->count() ?> Товаров</span>
                                        </div>
                                    </div><!-- ( CATEGORY IMAGE END ) -->
                                </a>
                            </div><!-- ( CATEGORY BOX END ) -->
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- ( CATEGORY ROW END ) -->
    </div>
</div><!-- ( STRIPE END ) -->
