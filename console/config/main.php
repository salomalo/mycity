<?php
use common\models\User;
use dektrium\user\Module;
use frontend\components\LangUrlManager;
use yii\log\FileTarget;
use yii\log\Logger;
use yii\i18n\PhpMessageSource;
use yii\web\Session;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'user' => [
            'class' => Module::className(),
            'mailer' => ['sender' => 'noreply@citylife.info'],
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => User::className(),
            'class' => User::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.citylife.info'],
        ],
        'session' => [
            'class' => Session::className(),
            'cookieParams' => ['domain' => '.citylife.info', 'path' => '/', 'httpOnly' => true],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::className(),
                    'levels' => ['error', 'warning'],
                    'categories' => ['yii\*'],
                ],
                [
                    'class' => FileTarget::className(),
                    'levels' => ['info'],
                    'logFile' => "@runtime/logs/parse.log",
                    'categories' => ['parse'],
                ],
            ],
        ],
        'logParse' => [
            'class' => Logger::className(),
            'targets' => [
                [
                    'class' => FileTarget::className(),
                    'levels' => ['info'],
                    'logFile' => "@runtime/logs/parse.log"
                ],
            ],
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
            'baseUrl' => "http://{$params['appFrontend']}",
            'rules' => [
                '<controller:\w+>/category/<pid>/page/<page>' => '<controller>/index',
                '<controller:\w+>/category/<pid>' => '<controller>/index',
                '<controller:\w+>/page/<page>' => '<controller>/index',
                '<controller:\w+>/<alias>' => '<controller>/view',
                '<controller>/<action>' => '<controller>/<action>',
            ]
        ],
        'urlManagerBackend' => [
            'class' => LangUrlManager::className(),
        ],
    ],
    'params' => $params,
];
