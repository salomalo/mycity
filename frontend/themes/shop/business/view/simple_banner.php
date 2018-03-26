<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $backgroundDisplay bool
 */

use common\models\File;
use common\models\Gallery;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$images = [];
$previews = [];

//Ищем галерею
$gallery = Gallery::find()
    ->select('id')
    ->where(['pid' => $model->id, 'type' => File::TYPE_BUSINESS])
    ->orderBy(['id' => SORT_ASC])
    ->asArray()->one();

if ($gallery) {
    /**
     * Ищем изображения галереи
     *
     * @var $files File[]
     */
    $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $gallery['id']])->all();

    //Получаем url изображений и миниатюр
    foreach ($files as $file) {
        $previews[] = Yii::$app->files->getUrl(new Gallery(), 'attachments', 100, $file->name, "business/{$gallery['id']}");
        $images[] = Yii::$app->files->getUrl(new Gallery(), 'attachments', 900, $file->name, "business/{$gallery['id']}");
    }
}
?>

<?php if ($images) : ?>
    <div class="detail-banner" style="margin-top: 7px; height: 400px;">
        <div class="detail-banner-shadow" style="background: none"></div>
        <div class="container">

            <div class="detail-banner-gallery">
                <div class="listing-detail-section" id="listing-detail-section-gallery">
                    <div class="listing-detail-gallery-wrapper">
                        <div class="listing-detail-gallery">

                            <?php foreach ($images as $id => $image) : ?>
                                <?php $span = Html::tag('span', null, ['class' => 'item-image', 'data' => ['background-image' => $image]]); ?>
                                <?= Html::a($span, $image, ['rel' => 'listing-gallery', 'data' => ['item-id' => $id]]) ?>
                            <?php endforeach; ?>

                        </div>

                        <div class="listing-detail-gallery-preview" data-count="<?= count($previews) ?>">
                            <div class="listing-detail-gallery-preview-inner">

                                <?php foreach ($previews as $id => $preview) : ?>
                                    <?php $img = Html::img($preview); ?>
                                    <?= Html::tag('div', $img, ['data' => ['item-id' => $id]]) ?>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>