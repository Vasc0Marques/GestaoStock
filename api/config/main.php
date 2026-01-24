<?php

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'controllerMap' => [
        'ping' => 'api\\controllers\\PingController',
    ],
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'csrfParam' => '_csrf-api',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 0,
            'useCookies' => false,
        ],
        'db' => require __DIR__ . '/db.php',
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,

            'rules' => [
                // Regras explícitas para endpoints principais
                'GET ping' => 'ping/index',
                'GET users' => 'users/index',

                // Regras explícitas para endpoints Android (ordem importa: mais específicas primeiro)
                // Autenticação
                'POST auth/login' => 'auth/login',
                'POST auth/verify-pin' => 'auth/verify-pin',


                // Materials
                'GET materials/search' => 'materials/search',
                'GET materials/<codigo:[^/]+>/movements' => 'materials/movements',
                'GET materials/<codigo:[^/]+>' => 'materials/view',

                // Movements
                'GET movements' => 'movements/index',
                'POST movements/out' => 'movements/out',

                // Orders
                'GET orders' => 'orders/index',
                'GET orders/<id:\d+>' => 'orders/view',
                'POST orders/<id:\d+>/receive' => 'orders/receive',

                // Regra REST fallback (apenas controllers corretos)
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'auth', 'users', 'materials', 'movements', 'orders'
                    ],
                    'pluralize' => false,
                ],
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->format = yii\web\Response::FORMAT_JSON;
            },
        ],
    ],
    'modules' => [
        // Se quiser usar módulos, adicione aqui
    ],
    'params' => array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        file_exists(__DIR__ . '/params-local.php') ? require(__DIR__ . '/params-local.php') : [],
        require(__DIR__ . '/params.php')
    ),
];
