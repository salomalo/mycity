<?php
use backend\controllers\AdminController;
use common\models\User;
use dektrium\user\Module;
use frontend\components\LangRequest;
use frontend\components\LangUrlManager;
use office\controllers\RecoveryController;
use office\controllers\RegistrationController;
use office\controllers\SecurityController;
use office\controllers\SettingsController;
use yii\i18n\PhpMessageSource;
use office\components\OfficeInit;
use dektrium\user\clients\Facebook;
use dektrium\user\clients\Twitter;
use dektrium\user\clients\VKontakte;
use yii\authclient\Collection;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'language' => 'ru-RU',
    'id' => 'app-office',
    'name' => 'Office CityLife',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'office\controllers',
    'bootstrap' => ['log', OfficeInit::className()],
    'modules' => [
        'user' => [
            'class' => Module::className(),
            'modelMap' => ['User' => ['class' => User::className()]],
            'controllerMap' => [
                'admin' => AdminController::className(),
                'registration' => RegistrationController::className(),
                'security' => SecurityController::className(),
                'recovery' => RecoveryController::className(),
                'settings' => SettingsController::className(),
            ],
            'rememberFor' => 600,
        ],
    ],
    'components' => [
        'view' => ['theme' => [
            'pathMap' => [
                '@app/views'            => '@app/themes/AdminLTE',
                '@dektrium/user/views' => '@app/themes/AdminLTE/user'
            ]],
        ],
        'user' => [
            'identityClass' => User::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.citylife.info'],
        ],
        'authClientCollection' => [
            'class'   => Collection::className(),
            'clients' => [
                'vkontakte' => [
                    'class'        => VKontakte::className(),
                    'clientId'     => '5329337',
                    'clientSecret' => 'XWwSGDFPvAuhC5xIiRzq',
                ],
                'facebook' => [
                    'class'        => Facebook::className(),
                    'clientId'     => '926646467419738',
                    'clientSecret' => '7ff64eb3c8aec94be4453591aa4e21a6',
                ],
                'twitter' => [
                    'class'          => Twitter::className(),
                    'consumerKey'    => 'SJ3jEKI9oypsQoku88xfA2eMU',
                    'consumerSecret' => 'cyxWe5L1AMrQlgc5nGXq6y0MsEeUDc0MCr6lL40o6vkdzkousM',
                ],
            ],
        ],
        'session' => ['cookieParams' => ['domain' => '.citylife.info', 'path' => '/', 'httpOnly' => true]],
        'errorHandler' => ['errorAction' => 'site/error'],
        'request' => [
            'class' => LangRequest::className(),
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'xxxxxxx',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => PhpMessageSource::className(),
                    'basePath' => '@office/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [],
                ],
            ],
        ],
        'urlManagerFrontend' => [
            'class' => LangUrlManager::className(),
            'baseUrl' => '',
            'hostInfo' => "http://{$params['appFrontend']}",
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'support' => 'site/support',
                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => LangUrlManager::className(),
            'rules' => [
                '/site/city' => '/site/city',
                '/site/login' => '/site/login',
                '/site/logout' => '/site/logout',
                '/site/showmodal-login' => '/site/showmodal-login',
                '/site/showmodal-signup' => '/site/showmodal-signup',
                '/site/singup-activate' => '/site/singup-activate',
                '/site/request-password-reset' => '/site/request-password-reset',
                '/site/showmodal-password-reset' => '/site/showmodal-password-reset',
                '/site/index' => '/site/index',
                '/user/login-from-frontend' => '/security/login-from-frontend',
                'business/get-all-product-category' => 'business/get-all-product-category',
                '/ads/addGallery' => '/ads/addGallery',
                '/ads/update-gallery' => '/ads/update-gallery',
                'business/get-all-product-category' => 'business/get-all-product-category',

                '<_c:[\w\-]+>' => '<_c>/index',
                '<controller>/<action>' => '<controller>/<action>',
                '<controller:\w+>/<alias>' => '<controller>/view',
            ]
        ],
        'urlManagerBackend' => ['class' => LangUrlManager::className()],
    ],
    'params' => $params,
];
