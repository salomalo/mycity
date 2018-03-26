<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\extensions\Comments\Comments;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use common\models\File;
?>

<?= CommentsSuperlist::widget([
    'id' => $model->id,
    'type' => File::TYPE_ACTION])
?>