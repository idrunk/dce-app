<?php
return [
    [
        'path' => 'project',
        'omissible_path' => true,
        'controller' => 'UiController->index',
        'render' => 'index.php',
    ],

    [
        'path' => 'im',
        'methods' => 'websocket',
    ],
    [
        'path' => 'im/login',
        'controller' => 'ImController->login',
    ],
    [
        'path' => 'im/logout',
        'controller' => 'ImController->logout',
    ],
    [
        'path' => 'im/signer',
        'controller' => 'ImController->loadSigner',
    ],
    [
        'path' => 'im/send',
        'controller' => 'ImController->sendMessage',
    ],
    [
        'path' => 'im/load',
        'controller' => 'ImController->loadMessage',
    ]
];