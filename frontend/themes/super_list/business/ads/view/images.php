<?php
use common\extensions\Gallery\Gallery;
use common\models\File;
use yii\helpers\Html;

?>

<?= Gallery::widget([
    'template' => 'super_list_ads',
    'model' => $model,
    'mongo' => true,
    'type' => File::TYPE_GALLERY,
]) ?>
