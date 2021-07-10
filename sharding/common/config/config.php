<?php
return [
    'id_generator' => [
        'client_rpc_hosts' => new ArrayObject(),  // 开启纯本地的ID生成器
    ],
    'log' => [
        'db' => [
            'console' => true, // 在控制台显示Sql语句
        ],
    ],
    '#extends' => [
        __DIR__ . '/sharding.php',
        APP_ROOT . '../.ignore/config/sharding.php',
    ],
];