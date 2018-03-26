<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 */

use common\models\File;
use common\models\Gallery;
use yii\helpers\Html;

$images = [];

//Ищем галерею
$gallery = Gallery::find()
    ->select('id')
    ->where(['pid' => $model->id, 'type' => File::TYPE_POST])
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
        $images[] = Yii::$app->files->getUrl(new Gallery(), 'attachments', 500, $file->name, "post/{$gallery['id']}");
    }
}
?>

<?php if ($images) : ?>
    <div class="blogGallery">
        <h4 class="borderBottom">Галерея</h4>
        <div class="row">
            <?php foreach ($images as $image) : ?>
                <div class="col-md-3 col-sm-6">
                    <div class="hoverStyle galleyBox">
                        <img src="<?= $image ?>" width="265" height="248" alt="">
                        <div class="hoverBox">
                            <div class="hoverIcons">
                                <a href="<?=$image ?>" class="eye hovicon popup" data-rel="gallery-2"><i class="fa fa-eye"></i></a>
                            </div><!-- ( HOVER ICONS END ) -->
                        </div><!-- ( HOVER BOX END ) -->
                    </div><!-- ( HOVER STYLE END ) -->
                </div>
            <?php endforeach; ?>
        </div>
    </div><!-- ( PHOTO GALLERY END ) -->
<?php endif; ?>