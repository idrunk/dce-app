<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 21:40
 */

namespace home\controller;

use dce\project\node\Node;
use dce\project\view\engine\ViewHttpXml;

class XmlController extends ViewHttpXml {
    #[Node('detail', urlSuffix: ['.xml'])]
    public function detail() {
        $this->assign('node1', 'value1');
        $this->assign('node2', ['a' => 1, 'b' => 2]);
    }
}