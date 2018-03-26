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

Вы уже являетесь владельцем предприятия "<?= $app->business->title ?>" на сайте <a href="<?= Url::to(['/'], true) ?>">CityLife</a>,
если вы владелец и этого предприятия, то обратитесь в поддержку support@citylife.info.