<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 18:58
 */

use service\AuthService;
use dce\event\Event;

return [
    'bootstrap' => function () {
        Event::on(Event::ENTERING_CONTROLLER, [AuthService::class, 'auth']);
    }
];