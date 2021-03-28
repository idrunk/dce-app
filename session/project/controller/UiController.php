<?php
namespace project\controller;

use dce\project\Controller;

class UiController extends Controller {
    public function index() {
        $hosts = [
            ['host' => '127.0.0.1', 'port' => 20460],
            ['host' => '127.0.0.1', 'port' => 20461],
        ];
        // 随机取一个Websocket服务器作为前端Websocket客户端要连接的主机
        $this->assignMapping($hosts[rand(0, count($hosts) - 1)]);
        $this->assign('title', 'SessionManager及Websocket分布式服务器示例');
    }
}