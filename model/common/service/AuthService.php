<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 19:00
 */

namespace service;

use dce\base\QuietException;
use dce\project\view\ViewCli;

class AuthService {
    public static function auth(ViewCli $view) {
        $nodeAuth = $view->request->node->extra['auth'] ?? 0;
        if ($nodeAuth > 0) {
            // 这里仅作Event用法演示, 真正鉴权中, 你应该取当前登录信息, 判断权限是否满足, 若不满足, 则作相应的响应
            $view->print('您尚未登录');
            // 抛出安静异常, 表示仅截断程序继续执行而不真正抛出
            throw new QuietException();
        }
    }
}