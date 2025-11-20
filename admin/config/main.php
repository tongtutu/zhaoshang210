<?php
$params = array_merge(
    require __DIR__ . '/../../bagesoft/config/params.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'debug' => [
        'class' => 'yii\debug\Module',
        // 添加允许访问调试工具的IP地址
        'allowedIPs' => ['127.0.0.1', '::1', '220.184.48.56'], 
    ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => '_backend',
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
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
