<?php
$params = array_merge(
    require __DIR__ . '/../common/config/params.php',
    require __DIR__ . '/../common/config/params-local.php',
    require __DIR__ . '/params.php',
    is_file(__DIR__ . '/params-local.php') ? require __DIR__ . '/params-local.php' : []
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__) . '/api',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'csrfParam' => '_csrf-api',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
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
            'enableStrictParsing' => true,
            'rules' => [
                'POST login' => 'auth/login',
                'GET encomendas/<id:\d+>' => 'encomendas/view',
                'GET movimentacoes' => 'movimentacao/index',
                'POST movimentacoes' => 'movimentacao/create',
                'GET stock/<id:\d+>' => 'stock/view',
            ],
        ],
        'db' => require __DIR__ . '/../common/config/db.php',
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
    ],
    'params' => $params,
];
