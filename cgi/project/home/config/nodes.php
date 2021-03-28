<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-02-16 16:42
 */

return [
    [
        'path' => 'home',
        'omissible_path' => true,
        'controller' => 'CommonController->index',
        'render' => 'common/index.php',
    ],
    [
        'path' => 'detail',
        'controller' => 'CommonController->detail',
    ],
    [
        'path' => 'add',
        'methods' => 'post',
        'controller' => 'CommonController->add',
    ],
];