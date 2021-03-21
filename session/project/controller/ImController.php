<?php
namespace project\controller;

use dce\project\request\Request;
use dce\service\server\ViewConnection;
use project\service\ImEngine;

class ImController extends ViewConnection {
    private ImEngine $imEngine;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->imEngine = new ImEngine($request);
    }

    public function signIn() {
        $signer = $this->imEngine->signIn($this->request->request['nickname'], $this->request->request['brief']);
        $this->response($signer, 'project/im/signer');
    }

    public function signOut() {
        $this->imEngine->signOut();
    }

    public function loadSigner() {
        [$signer, $signerList] = $this->imEngine->loadSigner();
        $this->response($signer ?: null);
        if ($signerList) {
            $this->response($signerList, 'project/im/sign_in');
        }
    }

    public function sendMessage() {
        $this->imEngine->sendMessage($this->request->request['message'], $this->request->request['target_mid']);
    }

    public function loadMessage() {
        $messageList = $this->imEngine->loadMessage($this->request->request['target_mid']);
        $this->response($messageList);
    }
}