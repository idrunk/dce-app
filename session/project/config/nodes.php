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
        'path' => 'im/sign_in',
        'controller' => 'ImController->signIn',
    ],
    [
        'path' => 'im/sign_out',
        'controller' => 'ImController->signOut',
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