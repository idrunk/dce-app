<?php
namespace utility\controller;

use dce\project\Controller;
use dce\project\node\Node;

class EnvController extends Controller {
    #[Node('env/get', lazyMatch: true)]
    public function get() {
        $info = [];
        foreach ($this->request->cli as $option => $v) {
            [$key, $value] = match ($option) {
                '-p', '--php' => ['PHP', phpversion()],
                '-s', '--system' => ['System', php_uname()],
                default => [0, 0],
            };
            if ($key) {
                $info[$key] = $value;
            }
        }
        $this->print(json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    #[Node('env/set', lazyMatch: true)]
    public function set() {

    }
}