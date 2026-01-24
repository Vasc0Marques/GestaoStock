<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__, 2),
    'controllerNamespace' => 'console\\controllers',
    'components' => [
        'db' => require __DIR__ . '/../../api/config/db.php',
        'authManager' => [
            'class' => 'yii\\rbac\\DbManager',
        ],
        'user' => [
            'class' => 'yii\\console\\User',
        ],
    ],
];
