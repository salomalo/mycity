<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use common\models\File;
use frontend\extensions\CommentStanza\CommentStanza;

?>

<?= CommentStanza::widget([
    'mongo' => true,
    'id' => $model->_id,
    'type' => File::TYPE_ADS])
?>
