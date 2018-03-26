<?php

namespace common\extensions\fileUploadWidget;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Gallery as GalleryModel;

/**
 * FileUploadUI
 *
 * Widget to render the jQuery File Upload UI plugin as shown in
 * [its demo](http://blueimp.github.io/jQuery-File-Upload/index.html)
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\fileupload
 */
class FileUploadUI extends BaseUpload
{
    /**
     * @var bool whether to use the Bootstrap Gallery on the images or not
     */
    public $gallery = true;
    public $url = '/';
    public $idGallery = 333;
    public $essence;
    public $type;
    public $idmodel;
    public $idform;
    public $isMongo;
    public $title = null;

    /**
     * @var array the HTML attributes for the file input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $fieldOptions = [];

    /**
     * @var string the ID of the upload template, given as parameter to the tmpl() method to set the uploadTemplate option.
     */
    public $uploadTemplateId;

    /**
     * @var string the ID of the download template, given as parameter to the tmpl() method to set the downloadTemplate option.
     */
    public $downloadTemplateId;

    /**
     * @var string the form view path to render the JQuery File Upload UI
     */
    public $formAddGalleryView = '@common/extensions/fileUploadWidget/views/formAddGallery';
    public $formView = '@common/extensions/fileUploadWidget/views/form';

    /**
     * @var string the upload view path to render the js upload template
     */
    public $uploadTemplateView = '@common/extensions/fileUploadWidget/views/upload';

    /**
     * @var string the download view path to render the js download template
     */
    public $downloadTemplateView = '@common/extensions/fileUploadWidget/views/download';

    /**
     * @var string the gallery
     */
    public $galleryTemplateView = '@common/extensions/fileUploadWidget/views/gallery';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->idmodel = (!$this->isMongo)? $this->model->id : $this->model->_id;
        $this->fieldOptions['multiple'] = true;
        $this->fieldOptions['id'] = ArrayHelper::getValue($this->options, 'id');

        $this->options['id'] .= '-form';
        $this->idform = $this->options['id'];
        $this->options['enctype'] = 'multipart/form-data';
        $this->options['class'] = 'myform';
        $this->options['uploadTemplateId'] = $this->uploadTemplateId ? : '#template-download';
        $this->options['downloadTemplateId'] = $this->downloadTemplateId ? : '#template-download';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->isMongo) {
            echo $this->render($this->formAddGalleryView, ['model' => $this->model, 'type' => $this->type, 'essence' => $this->essence]);
        } else {
            echo $this->render($this->formAddGalleryView, ['model' => $this->model, 'type' => $this->type, 'essence' => $this->essence, 'isMongo' => true]);
        }

        $where = (!$this->isMongo)?  ['pid' => $this->idmodel] : ['pid' => $this->idmodel];
        $gallery = GalleryModel::find()->where($where)->andWhere(['type' => $this->type])->all();

        $count = 0;

        $arr = [];
        if (is_array($gallery)) {
            foreach ($gallery as $item) {
                $count += 1;
                echo '<div class ="gallery_item">';

                echo '<li>';
                
                if(!$this->title){
                    echo $item->title;
                    echo ' <a href="' . \Yii::$app->urlManager->createUrl([
                        $this->essence . '/update-gallery',
                        'idGallery' => $item['id'],
                        'idmodel' => isset($this->isMongo) && $this->isMongo ? (string)$this->idmodel : $this->idmodel,
                    ]) . '" title = "Редактировать",><span class="glyphicon glyphicon-pencil"></span></a> ';
                    
                    echo ' <a href="' . \Yii::$app->urlManager->createUrl([
                        $this->essence . '/deleteGallery',
                        'idGallery' => $item['id'],
                        'essence' => $this->essence,
                        'idmodel' => isset($this->isMongo) && $this->isMongo ? (string)$this->idmodel : $this->idmodel,
                        'action' => 'delGallery'
                    ]) . '" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>';
                }
                else{
                    echo '<label class="control-label">' . $this->title . '</label>';
                }

                $this->options['id'] = $this->idform . $count;


                $this->clientOptions['url'] = \Yii::$app->urlManager->createUrl([$this->essence . '/uploadGallery', 'id' => $item['id'], 'essence' => $this->essence]); // url для ajax

                $this->url = [
                    $this->essence . '/uploadGallery', // url для формы
                    'id' => $item['id'],
                    'essence' => $this->essence
                ];

                $arr[] = [
                    $this->options['id'],
                    $this->clientOptions,
                    $item['id']
                ];
                echo $this->render($this->formView);
                echo $this->render($this->uploadTemplateView);
                echo $this->render($this->downloadTemplateView);

                echo '</li>';
                echo '</div>';
            }
        }

        $this->registerClientScript($arr, $this->essence);
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader UI
     */
    public function registerClientScript($arr, $essence)
    {
        $view = $this->getView();

        FileUploadUIAsset::register($view);

        $js = [];

        foreach ($arr as $item) {

            $options = Json::encode($item[1]);

            $js[] = "jQuery('#$item[0]').fileupload($options);";
            $js[] = "jQuery('#$item[0]').addClass('fileupload-processing');";
            $js[] = "$.ajax({
                        // Uncomment the following to send cross-domain cookies:
                        //xhrFields: {withCredentials: true},
                        url: '" . \Yii::$app->urlManager->createUrl([$essence . '/deleteGallery', 'action' => 'list', 'idGallery' => $item[2], 'essence' => $essence]) . "',
                        //acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                        dataType: 'json',
                        context: $('#$item[0]')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
                    }).done(function (result) {
                        $(this).fileupload('option', 'done')
                            .call(this, $.Event('done'), {result: result});
                    });
                ";
        }

        $view->registerJs(implode("\n", $js));
    }
}
