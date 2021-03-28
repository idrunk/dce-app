<?php
namespace home\controller\sub;

use dce\project\Controller;
use dce\project\node\Node;

class PathController extends Controller {
    #[
        Node,
        Node('ctrl', controllerPath: true),
    ]
    public function test() {
        $this->assign('feature', '本节点未指定path, 将自动以方法名 method 作为路径名, 因为当前控制器定义了 controllerPath, 并且处于子目录 sub 下, 所以完整的访问路径为 /home/sub/ctrl/test, '
            . '因为控制器路径节点也绑定在本节点中, 所以也可以通过路径 /home/sub/ctrl 访问');
    }
}