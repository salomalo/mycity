<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 */
use common\models\File;
use frontend\extensions\CommentStanza\CommentStanza;

?>

<?= CommentStanza::widget([
    'mongo' => false,
    'template' => 'kuteshop_post',
    'id' => $model->id,
    'type' => File::TYPE_POST])
?>
