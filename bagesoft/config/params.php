<?php
/**
 * 参数
 * @author        shuguang <5565907@qq.com>
 */
$webroot = '';
return [
    'adminEmail' => 'admin@example.com',
    'appid' => 'xxxx',
    'appkey' => 'fff',
    'allowDbExe' => 1,
    'res.url' => YII_ENV_DEV ? '//sqrrs.turingtown.com' : '//sqrrs.turingtown.com',
    'api.url' => YII_ENV_DEV ? '//localhost/poweryun/api/web' : (YII_ENV_TEST ? '//apis.dev.s7q.cn' : '//apis.s7q.cn'),
    'sms.logger' => true,
    'sms.driver' => 'winic',
    'sms.winic' => [
        'username' => 'hz_shangqi',
        'password' => 'shangqi@888',
    ],
    'email.smtp' => [
        'host' => 'smtpdm.aliyun.com',
        'username' => 'web@wi6.cn',
        'password' => 'ABc187a546ht32',
        'sender' => 'web@wi6.cn',
        'port' => 25,
        'encryption' => '',
        'charset' => 'utf8',
    ],
    'mq' => [
        'web2dc' => [ //由WEB向NODE发送数据
            'server' => [
                'host' => '127.0.0.1',
                'port' => 15672,
                'login' => 'admin',
                'password' => 'sqkj20250520',
                'vhost' => 'zjsuntree_zhaoshang',
                'debug' => false,
            ],
            'exchange' => 'dc.communication', //节点通信交换机
            'routes' => [],
        ],
    ],
    'bsVersion' => '5.x',
    'res.ver' => '1.0.1',
    'weburl'=>'https://squc.turingtown.com'
];
