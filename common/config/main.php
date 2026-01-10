<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            // 'on beforeLogin' => function ($event) {
            //     // Se for backend e o user for operador, bloqueia
            //     if (Yii::$app->id === 'app-backend') {
            //         $user = $event->identity;
            //         if ($user && $user->cargo === 'operador') {
            //             Yii::$app->session->setFlash('error', 'Operadores não têm acesso ao painel de administração.');
            //             Yii::$app->user->logout();
            //             Yii::$app->response->redirect(['/site/access-denied'])->send();
            //             Yii::$app->end();
            //         }
            //     }
            // },
        ],
    ],
];
