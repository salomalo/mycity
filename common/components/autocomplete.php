<?php
use mag\S3;
use yii\BaseYii;
use yii\di\Container;
use yii\web\Application;
use yii\web\User as UserBasic;
use yii\mongodb\Connection;
use common\components\files\Files;
use common\components\LiqPay\LiqPay;
use common\models\User;
use backend\models\Admin;
use frontend\components\LangRequest;
use frontend\components\LangUrlManager;


require(__DIR__ . '/../../vendor/yiisoft/yii2/BaseYii.php');

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It extends from [[\yii\BaseYii]] which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of [[\yii\BaseYii]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Yii extends BaseYii
{
    /**
     * @var WebApplication the application instance
     */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(__DIR__ . '/../../vendor/yiisoft/yii2/classes.php');
Yii::$container = new Container();

/**
 * Class WebApplication
 *
 * @property DetailLogger $logger
 * @property LiqPay $liqPay
 * @property S3 $s3
 * @property Files $files
 * @property \yii\caching\FileCache $cacheFrontend
 * @property \yii\caching\FileCache $cacheOffice
 * @property UserIDEHelper $user
 * @property LangRequest $request
 * @property LangUrlManager $urlManagerOffice
 * @property LangUrlManager $urlManagerBackend
 * @property LangUrlManager $urlManagerFrontend
 * @property Connection $mongodb
 * @property \frontend\components\Search $search
 * @property \yii\sphinx\Connection $sphinx
 */
class WebApplication extends Application {}

/**
 * Class UserIDEHelper
 *
 * @property User|Admin $identity
 */
class UserIDEHelper extends UserBasic
{}
