<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-02-16 16:43
 */

namespace home\controller;

use dce\project\view\engine\ViewHttpHtml;

class CommonController extends ViewHttpHtml {
    public function index() {
        $this->assign('title', '恭喜你成功访问了Web首页！');
        $this->assign('time', date('Y-m-d H:i:s'));
    }
}