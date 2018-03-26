<?php
use frontend\components\LangRequest;
use frontend\components\LangUrlManager;
use yii\i18n\PhpMessageSource;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-m',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'm\controllers',
    'defaultRoute' => 'business/create',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.citylife.info'],
        ],
        'session' => [
            'cookieParams' => ['domain' => '.citylife.info'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
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
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [],
                ],
            ],
        ],
        'urlManager' => [
            'class' => LangUrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/city' => '/site/city',
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/site/showmodal-login' => '/site/showmodal-login',
                '/site/showmodal-signup' => '/site/showmodal-signup',
                '/singup-activate' => '/site/singup-activate',
//                '' => 'site/index',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<controller>/<action>' => '<controller>/<action>',
                '<controller:\w+>/<alias>' => '<controller>/view',
//            '<controller:\w+>/<pid>' => '<controller>/index',
            ]
        ],
    ],
    'params' => $params,
];
