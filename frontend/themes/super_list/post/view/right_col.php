<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use frontend\extensions\AdBlock;
use yii\helpers\Html;

$image = $model->image ? Yii::$app->files->getUrl($model, 'image', 200) : null;
$date = new DateTime($model->dateCreate);
?>

<?php if ($image) : ?>
    <div class="col-sm-4 col-lg-3">
        <div id="secondary" class="secondary sidebar">
            <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

            <div id="listing_author-2" class="widget widget_listing_author">
                <div class="widget-inner">
                    <div class="listing-author">
                        <div class="other">
                            <span class="date"><?= $date->format('d.m.Y, H:i') ?></span>
                        </div>
                        <?= Html::a(Html::img($image), null, ['class' => 'listing-author-image', 'data' => ['background-image' => $image]]) ?>
                    </div>
                </div>
            </div>

            <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
        </div>
    </div>
<?php endif; ?>