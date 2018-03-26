<?php
use frontend\extensions\Related\Related;

?>

<?= Related::widget([
    'title' => Yii::t('action', 'Similar_shares_of_headings'),
    'idCategory' => $model->idCategory,
    'idModel' => $model->id,
    'idCity' => (!empty(Yii::$app->params['SUBDOMAINID'])) ? Yii::$app->params['SUBDOMAINID'] : null,
    'model' => 'common\models\Action',
    'limit' => 6,
    'view' => 'action_super_list'
]); ?>
