<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 23.08.16
 * Time: 15:29
 *
 * @var $app \common\models\BusinessOwnerApplication
 */

use yii\helpers\Url;

?>

Вы подтверждаете что вы владелец предприятия "<?= $app->business->title ?>" на сайте <a href="<?= Url::to(['/'], true) ?>">CityLife</a>,
если да, то нажмите  <a href="<?= Url::to(['/business/application-accept', 'token' => $app->token], true) ?>">тут</a>