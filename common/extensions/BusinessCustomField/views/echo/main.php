<?php
/**
 * @var $this \yii\base\View
 * @var $model \common\models\Business
 */
use yii\helpers\Url;
?>
<a class="btn btn-info" id="load-custom-field">Получить кастомфилды</a>
<div id="business-custom-field" data-business_id="<?= $model->id ?>" data-url="<?= Url::to(['/business/custom-field-form']) ?>"></div>