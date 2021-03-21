<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 04:40
 */

return [
    'id_generator' => [
//        'client_storage' => '\dce\sharding\id_generator\bridge\IdgStorageRedis',
//        'server_rpc_hosts' => ['host' => '127.0.0.1', 'port' => '20470'],
    ],
    'redis' => [
        'host' => '192.168.1.222', // 这里替换成你的Redis主机IP
        'port' => 6379,
    ],
];