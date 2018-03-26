<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var yii\widgets\ActiveForm $form
 */

use backend\extensions\BusinessFormWidget\BusinessFormWidget;
use backend\extensions\MyStarRating\MyStarRating;
use backend\models\Admin;
use common\extensions\BusinessCustomField\EchoCustomField;
use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\ProductCategory;
use common\models\User;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\extensions\MultiView\MultiView;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\models\File;
use common\extensions\fileUploadWidget\FileUploadUI;
use common\extensions\BusinessPrice\BusinessPrice;
use common\models\Sitemap;
use yii\helpers\ArrayHelper;
use common\models\Tag;

$admin = Yii::$app->user->identity;
?>

<?= BusinessFormWidget::widget([
    '_view' => '_address',
    'data' => $address,
    'relModelName' => 'common\models\BusinessAddress',
    'readOnly' => false,
    'mapWidth' => '640px',
    'getDataByAjax' => false,
    'model' => $model,
    'essence' => $this->context->id,
]) ?>