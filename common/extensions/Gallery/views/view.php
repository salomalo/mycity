<?php
/**
 * @var $galList array
 * @var $options array
 * @var $model object
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\Pjax;
?>

<h4 class="predpr-album"><?= Yii::t('business', 'Photo'), ' ', $model->title ?>:</h4>

<?php if ($galList): ?>
    <?php Pjax::begin(['id' => 'form-select-gallery']); ?>
        <div id="gallery" class="ad-gallery">
            <div class="select-gallery-center">
                <?php $form = ActiveForm::begin(['options' => ['id' => 'select-gallery', 'data-pjax' => true]]); ?>
                    <?php if(count($options) > 1):?>
                        <span class="albums">Альбомы: 
                            <select name="idGallery">
                                <?php foreach ($options as $item): ?>
                                    <?= $item ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    <?php endif;?>
                <?php ActiveForm::end(); ?>
            </div>

            <div class="ad-image-wrapper">
            </div>
            <div class="ad-controls">
            </div>
            <div class="ad-nav">
                <div class="ad-thumbs">
                    <ul class="ad-thumb-list">  

                        <?php foreach ($galList as $img): ?>
                            <li>
                                <?php $echoImg = Yii::$app->files->getUrl($gal, 'attachments', 100, $img->name, $this->context->id.'/'.$gallery['id']); ?>
                                <?php $fullImg = Yii::$app->files->getUrl($gal, 'attachments', 500, $img->name, $this->context->id.'/'.$gallery['id']); ?>
                                <?= Html::a(Html::img($echoImg, ['title'=>'', 'alt'=>'']), $fullImg) ?>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        </div>
    <?php Pjax::end(); ?>
<?php endif;?>