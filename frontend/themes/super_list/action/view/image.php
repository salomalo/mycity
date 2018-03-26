<?php
use yii\helpers\Html;
?>

<?php if ($model->image): ?>
    <div class="listing-detail-section" id="listing-detail-section-action-image">
        <div class="listing-detail-attributes">

            <div class="listing-detail-gallery-wrapper">
                <div class="listing-detail-gallery">

                        <?php $span = Html::tag('span', null, ['class' => 'item-image', 'data' => ['background-image' => Yii::$app->files->getUrl($model, 'image', 285)]]); ?>
                        <?= Html::a($span, Yii::$app->files->getUrl($model, 'image', 285), ['rel' => 'listing-gallery', 'data' => ['item-id' => 1]])?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
