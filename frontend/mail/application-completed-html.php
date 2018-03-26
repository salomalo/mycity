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

Право на управление предприятием "<?= $app->business->title ?>" на сайте <a href="<?= Url::to(['/'], true) ?>">CityLife</a> подтверждено.
Чтобы начать нажмите <a href="http://<?= Yii::$app->params['appOffice'] ?>">тут</a>