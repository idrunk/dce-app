<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-02-16 16:43
 */

namespace home\controller;

use dce\project\Controller;

class CommonController extends Controller {
    public function index() {
        $this->assign('title', '恭喜你成功访问了Cgi Web示例页面！');
        $this->assign('time', date('Y-m-d H:i:s'));
    }

    public function detail() {
        $this->assign('key', 'Hello world !');
    }

    public function add() {
        $this->success('保存成功');
    }
}