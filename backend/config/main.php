<?php
use backend\components\InitCities;
use frontend\components\LangUrlManager;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'language' => 'ru-RU',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', InitCities::className()],
    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            'treeStructure' => [
                'treeAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
            'dataStructure' => [
                'keyAttribute' => 'id',
                'nameAttribute' => 'title',
                'iconAttribute' => 'icon',
                'iconTypeAttribute' => 'icon_type'
            ]
            // other module settings, refer detailed documentation
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
        ],
        'request' => [
            'class' => 'frontend\components\LangRequest',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'xxxxxxx',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => LangUrlManager::className(),
            'rules' => [
                '<_c:[\w\-]+>' => '<_c>/index',
                '' => 'site/index',
            ]
        ],
        'urlManagerBackend' => ['class' => LangUrlManager::className()],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/themes/adminlte236',
                    '@dektrium/user/views' => '@backend/themes/adminlte236/user'
                ],
            ],
        ],

    ],

    'params' => $params,
];
