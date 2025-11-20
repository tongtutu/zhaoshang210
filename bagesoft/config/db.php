<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=zjsuntree_zhaoshang',
    'username' => 'zjsuntree_zhaoshang',
    'password' => 'sqkj2025',
    'charset' => 'utf8mb4',
    'tablePrefix' => 'tb_',
    'attributes' => [
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
