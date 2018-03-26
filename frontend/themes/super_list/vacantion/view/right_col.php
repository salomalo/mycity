<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\WorkVacantion
 */

use frontend\extensions\AdBlock;
use yii\helpers\Html;

$image = ($model->company && $model->company->image) ? Yii::$app->files->getUrl($model->company, 'image', 200) : null;
?>

<?php if(isset($model->company)) : ?>
    <div class="col-sm-4 col-lg-3">
        <div id="secondary" class="secondary sidebar">
            <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

            <div id="listing_author-2" class="widget widget_listing_author">
                <div class="widget-inner">
                    <div class="listing-author">
                        <?php if($model->company->image):?>
                            <?= Html::a(Html::img($image), null, ['class' => 'listing-author-image', 'data' => ['background-image' => $image]]) ?>
                        <?php endif;?>
                    </div>
                </div>
            </div>

            <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
        </div>
    </div>
<?php endif; ?>