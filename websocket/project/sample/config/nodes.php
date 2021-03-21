<?php
/**
 * Author: Drunk
 * Date: 2016-11-27 1:53
 */

return [
    [
        'path' => 'sample',
        'omissible_path' => 1,
        'name' => 'IM界面',
        'controller' => 'ImUiController->ui',
        'php_template' => 'im/ui.php',
        'template_layout' => '',
    ],

    [
        'methods' => 'websocket',
        'path' => 'im',
    ],

    [
        'path' => 'im/login',
        'name' => '登录',
        'controller' => 'ImServiceController->signIn',
    ], [
        'path' => 'im/send',
        'name' => '发送消息',
        'controller' => 'ImServiceController->send',
    ], [
        'path' => 'im/logout',
        'name' => '退出登录',
        'controller' => 'ImServiceController->signOut',
    ]
];
