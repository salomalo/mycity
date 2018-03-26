<?php

use frontend\components\RedirectList;
use frontend\controllers\SettingsController;
use yii\authclient\Collection;
use yii\i18n\PhpMessageSource;
use dektrium\user\clients\Facebook;
use dektrium\user\clients\Twitter;
use dektrium\user\clients\VKontakte;
use dektrium\user\Module;
use common\components\CityRequest;
use common\components\InitCities;
use frontend\components\LangRequest;
use frontend\components\LangUrlManager;
use common\models\RegistrationForm;
use common\models\User;
use frontend\controllers\ProfileController;
use frontend\controllers\RegistrationController;
use frontend\controllers\SecurityController;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'language' => 'ru-RU',
    'id' => 'app-frontend',
    'name' => 'CityLife',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => [
        'log',
        RedirectList::className(),
        InitCities::className(),
        CityRequest::className(),
    ],
    'modules' => [
        'user' => [
            'class' => Module::className(),
            'mailer' => ['sender' => 'noreply@citylife.info'],
            'admins' => [],
            'modelMap' => [
                'User' => ['class' => User::className()],
                'RegistrationForm' => RegistrationForm::className(),
            ],
            'controllerMap' => [
                'registration' => RegistrationController::className(),
                'security' => SecurityController::className(),
                'profile' => ProfileController::className(),
                'settings' => SettingsController::className()
            ],
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => User::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.citylife.info'],
        ],
        'session' => [
            'cookieParams' => ['domain' => '.citylife.info', 'path' => '/', 'httpOnly' => true],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views'            => '@app/themes/super_list',
                    '@dektrium/user/views' => '@app/themes/super_list/user'
                ],
//                'pathMap' => [
//                    '@app/views'            => '@app/themes/classic',
//                    '@dektrium/user/views' => '@app/themes/classic/user'
//                ],
//                'pathMap' => [
//                    '@app/views'            => '@app/themes/three_cols',
//                    '@dektrium/user/views' => '@app/themes/three_cols/user'
//                ],
            ],
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
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [],
                ],
            ],
        ],
        'urlManagerBackend' => [
            'class' => LangUrlManager::className(),
            'baseUrl' => '',
            'hostInfo' => "http://{$params['appBackend']}",
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
        ],
        'urlManager' => [
            'class' => LangUrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'rss.xml'                                                           => 'site/rss',
                'profile/update'                                                    => 'user/profile/update',
                'profile'                                                           => 'user/profile/index',
                'map/business/category/<pid>'                                       => 'business/map',
                'map/business'                                                      => 'business/map',

                'business/top/category/<pid>'                                       => 'business/top',
                'business/top'                                                      => 'business/top',
                'business/category/<pid>/page/<page>'                               => 'business/index',
                'business/category/<pid>'                                           => 'business/index',
                'business/page/<page>'                                              => 'business/index',
                'business/<aliasBusiness>/ads/<alias>'                              => 'ads/view',
                'business/<alias>/commentList'                                      => '/comment/list',
                'business/<alias>/shopping-cart'                                    => '/shopping-cart/index',
                'business/<alias>/shopping-cart/view'                               => '/shopping-cart/view',
                'business/<alias>/about'                                            =>  'business/about',
                'business/<alias>/contact'                                          =>  'business/business-contact',
                'business/<alias>/blog/<aliasPost>'                                 => 'post/view-business-post',
                'business/<alias>/blog'                                             => 'post/business-blog-index',
                'business/<alias>/search'                                           => 'business/search',
                'business/<alias>/<urlCategory>'                                     => 'business/goods',
                //'business/goods/<alias>'                                            => 'business/goods',
                'business/action/<alias>'                                           => 'business/action',
                'business/afisha/<alias>'                                           => 'business/afisha',
                '/business/check-companys'                                          => 'business/check-companys',
                '/business/product-category-list'                                   => 'business/product-category-list',
                '/business/city-list'                                               => 'business/city-list',
                '/business/city-by-business'                                        => 'business/city-by-business',
                '/business/rating-change'                                           => 'business/rating-change',
                'business/business-category-by-business'                            => 'business/business-category-by-business',
                'business/get-all-product-category'                                 => 'business/get-all-product-category',
                '/business/owner-application'                                       => 'business/owner-application',
                '/business/application-accept'                                      => 'business/application-accept',
                '/business/contact'                                                 => 'business/contact',
                '/business/list-address'                                            => 'business/list-address',
                '/business/callback'                                                => 'business/callback',
                '/business/success-paid'                                            => 'business/success-paid',
                '/business/<alias>'                                                 => 'business/view',
                '/business'                                                         => 'business/index',
                'business/ads-view/<alias>'                                         => 'business/ads-view',
                'business/action/<alias>'                                           => 'business/action',
                'business/afisha/<alias>'                                           => 'business/afisha',
                'business/<alias>/<urlCategory>'                                    => 'business/goods',
                '/business/check-companys'                                          => '/business/check-companys',
                'site/add-favorite'                                                 => 'site/add-favorite',

                'afisha/<archive:archive>/category/<pid>/genre/<genre>/page/<page>' => 'afisha/index',
                'afisha/<archive:archive>/category/<pid>/genre/<genre>'             => 'afisha/index',
                'afisha/<archive:archive>/category/<pid>/page/<page>'               => 'afisha/index',
                'afisha/<archive:archive>/category/<pid>'                           => 'afisha/index',
                'afisha/<archive:archive>/genre/<id>'                               => 'afisha/index',
                'afisha/<archive:archive>'                                          => 'afisha/index',

                'afisha/week/category/<pid>/genre/<id>'                             => 'afisha/week',
                'afisha/week/category/<pid>'                                        => 'afisha/week',
                'afisha/week/genre/<id>'                                            => 'afisha/week',

                'afisha/soon/category/<pid>/genre/<id>'                             => 'afisha/soon',
                'afisha/soon/category/<pid>'                                        => 'afisha/soon',
                'afisha/soon/genre/<id>'                                            => 'afisha/soon',

                'afisha/now/category/<pid>/genre/<id>'                              => 'afisha/now',
                'afisha/now/category/<pid>'                                         => 'afisha/now',
                'afisha/now/genre/<id>'                                             => 'afisha/now',
                'afisha/now'                                                        => 'afisha/now',

                'action/<archive:archive>/category/<pid>/page/<page>'               => 'action/index',
                'action/<archive:archive>/category/<pid>'                           => 'action/index',
                'action/<archive:archive>'                                          => 'action/index',
                'action/<sort:time>/category/<pid>/page/<page>'                     => 'action/index',
                'action/<sort:time>/category/<pid>'                                 => 'action/index',
                'action/<sort:time>/page/<page>'                                    => 'action/index',
                'action/<sort:time>'                                                => 'action/index',

                'sphinx'                                                            => 'site/sphinx',
//                'search/<s>'                                                        => 'search/index',
//                'search/<s>/city/<id_city>'                                         => 'search/index',
//                'search/<s>/city/<id_city>/type/<type>'                             => 'search/index',
//                'search/<s>/city/<id_city>/type/<type>/page/<page>'                 => 'search/index',

                'search/<action>/category/<pid:\d+>/<s:\w+>/page/<page:\d+>'        => 'search/<action>',
                'search/<action>/<s:\w+>/page/<page:\d+>'                           => 'search/<action>',
                'search/<action>/category/<pid:\d+>/<s:\w+>'                        => 'search/<action>',
                'search/<action>/<s:\w+>'                                           => 'search/<action>',
                'search/<action>/category/<pid:\d+>'                                => 'search/<action>',
                'search/<action>/page/<page:\d+>'                                   => 'search/<action>',
                'search/<action>'                                                   => 'search/<action>',

                '<controller:\w+>/category/<pid>/genre/<genre>/page/<page>'         => '<controller>/index',
                '<controller:\w+>/category/<pid>/genre/<genre>'                     => '<controller>/index',

                '<controller:\w+>/category/<pid>/page/<page>'                       => '<controller>/index',
                '<controller:\w+>/category/<pid>'                                   => '<controller>/index',

                '<controller:\w+>/genre/<id>'                                       => '<controller>/index',
                '<controller:\w+>/compare/<id>'                                     => '<controller>/compare',
                '<controller:\w+>/page/<page>'                                      => '<controller>/index',

                'site/liqpay'                               => 'site/liqpay',
                'site/liqpay-callback'                      => 'site/liqpay-callback',
                'site/owner-support'                        => 'site/owner-support',
                '/site/request-password-reset'              => '/site/request-password-reset',
                '/file/add-files'                           => '/file/add-files',
                '/ads/delete-some-image'                    => '/ads/delete-some-image',
                '/ads/rating-change'                        => '/ads/rating-change',
                '/product/rating-change'                    => '/product/rating-change',
                '/comment/add'                              => '/comment/add',
                '/comment/add-superlist'                    => '/comment/add-superlist',
                '/comment/delete'                           => '/comment/delete',
                '/comment/rating'                           => '/comment/rating',
                '/comment/update'                           => '/comment/update',
                //'/comment/list'                             => '/comment/list',
                '/comment/create-ads'                       => '/comment/create-ads',
                '/site/city'                                => '/site/city',
                '/site/login'                               => '/site/login',
                '/site/logout'                              => '/site/logout',
                '/contacts'                                 => '/site/contacts',
                '/reklama'                                  => '/site/reklama',
                '/support'                                  => '/site/support',
                '/complaints'                               => '/site/complaints',
                '/action-company'                           => '/site/action-company',
                '/official-info'                            => '/site/official-info',
                '/site-rules'                               => '/site/site-rules',
                '/site/captcha'                             => '/site/captcha',
                '/site/get-ajax'                            => '/site/get-ajax',
                '/about-city'                               => '/site/about-city',
                'image/<alias>/<url>'                       => '/site/image',

                '/site/showmodal-login'                     => '/site/showmodal-login',
                '/site/showmodal-password-reset'            => '/site/showmodal-password-reset',
                '/site/showmodal-signup'                    => '/site/showmodal-signup',
                '/site/account-activate'                    => '/site/account-activate',
                '/site/showmodal-message-change-city'       => '/site/showmodal-message-change-city',
                '/site/showmodal-message-login-auth'        => '/site/showmodal-message-login-auth',
                '/site/showmodal-message'                   => '/site/showmodal-message',
                '/site/showmodal-contact'                   => '/site/showmodal-contact',
                '/site/showmodal-complaint'                 => '/site/showmodal-complaint',
                '/shopping-cart/add-shopping-cart'          => '/shopping-cart/add-shopping-cart',
                '/shopping-cart/update-shopping-cart'       => '/shopping-cart/update-shopping-cart',
                '/shopping-cart/delete/<id>'                => '/shopping-cart/delete',
                '/shopping-cart/change-item'                => '/shopping-cart/change-item',
                '/ads/business-contact'                     => '/ads/business-contact',
                '/shopping-cart/clear-shopping-cart'        => '/shopping-cart/clear-shopping-cart',

                '/landing/callback'                         => '/landing/callback',
                '/landing/subscribe'                        => '/landing/subscribe',
                'action/business-list'                      => 'action/business-list',
                'afisha/business-list'                      => 'afisha/business-list',
                'afisha/soon'                               => 'afisha/soon',
                'afisha/week'                               => 'afisha/week',

                '/landing'                                  => '/landing/index',
                '/afisha'                                   => '/afisha/index',
                '/action'                                   => '/action/index',
                '/post'                                     => '/post/index',
                '/vacantion'                                => '/vacantion/index',
                '/resume'                                   => '/resume/index',
                '/ads'                                      => '/ads/index',
                '/shopping-cart'                            => '/shopping-cart/index',
                '/landing/order-consultation'               => '/landing/order-consultation',

                '/<short_url>'                              => '/business/view-by-short-url',

                '<_c:[\w\-]+>'                              => '<_c>/index',
                '<controller:\w+>/select/<idModel>'         => '<controller>/select',
                '<controller:\w+>/create'                   => '<controller>/create',
                '<controller:\w+>/update/<alias>'           => '<controller>/update',
                '<controller:\w+>/search'                   => '<controller>/search',
                'product/view/<alias>'                      => 'product/view',
                '<controller:\w+>/<alias>'                  => '<controller>/view',
                ''                                          => 'site/index',
                '<controller>/<action>'                     => '<controller>/<action>',
            ]
        ],
        'search' => [
            'class' => '\frontend\components\Search',
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=9306;',
            'username' => '',
            'password' => '',
        ],
    ],
    'params' => $params,
];
