<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 21:39
 */

namespace home\controller;

use dce\project\Controller;
use dce\project\node\Node;

class HtmlController extends Controller {
    #[Node('home', render: 'index.php', omissiblePath: true)]
    public function index() {
        $this->assign('title', '测试标题');
        $this->assign('content', '测试内容');
    }

    #[Node('about', render: 'about.php')]
    public function about() {}
}