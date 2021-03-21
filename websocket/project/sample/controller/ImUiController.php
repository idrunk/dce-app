<?php
/**
 * Author: Drunk
 * Date: 2017-3-24 19:23
 */

namespace sample\controller;

use dce\Dce;
use dce\project\view\engine\ViewHttpHtml;

class ImUiController extends ViewHttpHtml {
    public function ui() {
        $host = '127.0.0.1';
        $port = 20461;

        $this->assignMapping([
            'host' => $host,
            'port' => $port,
        ]);
    }
}
