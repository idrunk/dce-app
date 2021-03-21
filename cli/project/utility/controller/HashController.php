<?php
namespace utility\controller;

use dce\project\node\Node;
use dce\project\request\Request;
use dce\project\view\ViewCli;

class HashController extends ViewCli {
    #[Node('utility', omissiblePath: true)]
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    #[Node('hash', lazyMatch: true)]
    public function run() {
        if ($this->request->cli['-i'] ?? $this->request->cli['--input'] ?? 0) {
            $content = $this->input('请输入需要编码的内容: ');
            $type = $this->input('请输入编码方式(sha1): ') ?: 'sha1';
        } else {
            $content = $this->request->cli['-c'] ?? $this->request->cli['--content'] ?? $this->rawRequest->remainingPaths[0] ?? null;
            $type = $this->request->cli['-t'] ?? $this->request->cli['--type'] ?? 'sha1';
        }
        $this->printf('"%s"的%s值为: %s', $content, $type, hash($type, $content));
    }
}