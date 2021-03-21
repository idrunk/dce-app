<?php
return [
    'mysql' => [
        'default' => [ // 单分库模式配置
            'host' => '192.168.1.222',
            'db_user' => 'root',
            'db_password' => 'drunk',
            'db_name' => 'sample_db1',
            'db_port' => 3306,
        ],
        'db2' => [ // 主从复制库模式配置, (各种模式配置互相兼容, Dce最终会处理成相同格式)
            [
                'host' => '192.168.1.222',
                'db_user' => 'root',
                'db_password' => 'drunk',
                'db_name' => 'sample_db2',
                'db_port' => 3306,
                'is_master' => 1,
            ],
        ],
    ],
    'sharding' => [
        'member' => [
            'db_type' => 'mysql',
            'type' => 'modulo',
            'cross_update' => true,
            'allow_joint' => true,
            'table' => [
                'member' => [
                    'id_column' => 'mid',
                ],
                'member_badge_map' => [
                    'sharding_column' => 'mid',
                ],
                'member_sign_in' => [
                    'id_column' => ['name' => 'id', 'tag' => 'msi_id'],
                    'sharding_column' => 'mid',
                ],
            ],
            'mapping' => [
                'default' => 0,
                'db2' => 1,
            ]
        ],
    ],
];