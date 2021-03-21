<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 21:39
 */

namespace home\controller;

use dce\project\node\Node;
use dce\project\view\engine\ViewHttpHtml;

class HtmlController extends ViewHttpHtml {
    #[Node('home', phpTemplate: 'index.php', omissiblePath: true)]
    public function index() {
        $this->assign('title', '测试标题');
        $this->assign('content', '测试内容');
    }

    #[Node('about', phpTemplate: 'about.php')]
    public function about() {}
}