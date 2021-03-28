<?php
namespace home\controller;

use dce\project\Controller;
use dce\project\node\Node;

class PathController extends Controller {
    #[Node('ctrl', controllerPath: true)]
    public function __() {}

    #[Node]
    public function test() {
        $this->assign('feature', '本节点未指定path, 将自动以方法名 method 作为路径名, 因为当前控制器定义了 controllerPath, 所以完整的访问路径为 /home/ctrl/test');
    }
}