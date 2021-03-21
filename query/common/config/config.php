<?php
return [
    'id_generator' => [
        'client_rpc_hosts' => new ArrayObject(),  // 开启纯本地的ID生成器
    ],
    'mysql' => [ // 单库版
        'host' => '127.0.0.1',
        'db_user' => 'root',
        'db_password' => 'drunk',
        'db_name' => 'sample_db1',
        'db_port' => 3306,
    ]
];