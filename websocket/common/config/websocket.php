<?php
/**
 * Author: Drunk (drunkce.com; idrunk.net)
 * Date: 2020/4/26 20:33
 */

return [
    'websocket' => [
        'extra_ports' => [
            ['host' => '0.0.0.0', 'port' => 20464], // 同时开启该端口作为Http与Websocket服务端口
        ],
        'service' => '\\sample\\service\\ImWebsocketServer', // 将服务器类设为自定义类
        'enable_http' => true,
        'enable_tcp_ports' => [ // 同时开启Tcp与Udp服务与端口
            ['host' => '0.0.0.0', 'port' => 20462, 'sock_type' => SWOOLE_SOCK_TCP],
            ['host' => '0.0.0.0', 'port' => 20463, 'sock_type' => SWOOLE_SOCK_UDP],
        ],
    ],
];
