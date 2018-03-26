<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use yii\helpers\Html;

?>
<?php if ($model->image || $model->description) : ?>
    <div class="listing-detail-section" id="listing-detail-section-description">
        <h2 class="page-header"><?= Yii::t('business', 'Detailed_Description') ?></h2>

        <div class="listing-detail-description-wrapper">
            <?php if ($model->image): ?>
                <?= Html::a(Html::img(Yii::$app->files->getUrl($model, 'image', 285), ['class' => 'big-img', 'alt' => $model->title]),
                    Yii::$app->files->getUrl($model, 'image'),
                    ['class' => 'fancybox'])
                ?>
            <?php endif; ?>
            <p><?= $model->description ?></p>
        </div>
    </div>
<?php endif; ?>