<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 15:34
 */
namespace home\controller;

use dce\project\view\engine\ViewHttpJson;

class JsonController extends ViewHttpJson {
    public function detail() {
        $this->assign('key', 'Hello world !');
    }

    public function add() {
        $this->success('保存成功');
    }
}