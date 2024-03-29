<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace common\extensions\fileUploadWidget;

use yii\web\AssetBundle;

/**
 * FileUploadUIAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\fileupload
 */
class FileUploadUIAsset extends AssetBundle
{
    public $sourcePath = '@common/extensions/fileUploadWidget/assets/';

    public $css = [
        'blueimp-file-upload/css/jquery.fileupload.css',
        'css/fileUpload.css'
    ];

    public $js = [
        'blueimp-file-upload/js/vendor/jquery.ui.widget.js',
        'blueimp-tmpl/js/tmpl.min.js',
        'blueimp-load-image/js/load-image.min.js',
        'blueimp-canvas-to-blob/js/canvas-to-blob.min.js',
        'blueimp-file-upload/js/jquery.iframe-transport.js',
        'blueimp-file-upload/js/jquery.fileupload.js',
        'blueimp-file-upload/js/jquery.fileupload-process.js',
        'blueimp-file-upload/js/jquery.fileupload-image.js',
        'blueimp-file-upload/js/jquery.fileupload-audio.js',
        'blueimp-file-upload/js/jquery.fileupload-video.js',
        'blueimp-file-upload/js/jquery.fileupload-validate.js',
        'blueimp-file-upload/js/jquery.fileupload-ui.js',

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 