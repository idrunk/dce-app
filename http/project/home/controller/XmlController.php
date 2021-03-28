<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 21:40
 */

namespace home\controller;

use dce\project\Controller;
use dce\project\node\Node;
use dce\project\render\Renderer;

class XmlController extends Controller {
    #[Node('detail', render: Renderer::TYPE_XML, urlSuffix: ['.xml'])]
    public function detail() {
        $this->assign('node1', 'value1');
        $this->assign('node2', ['a' => 1, 'b' => 2]);
        $this->assign('node3', [1, 2, 3]);
    }
}