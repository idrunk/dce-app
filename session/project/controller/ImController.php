<?php
namespace project\controller;

use dce\project\Controller;
use project\service\ImEngine;

class ImController extends Controller {
    private ImEngine $imEngine;

    public function __init(): void {
        $this->imEngine = new ImEngine($this->request);
    }

    public function login() {
        $signer = $this->imEngine->login($this->request->request['nickname'], $this->request->request['brief']);
        $this->response($signer, 'project/im/signer');
    }

    public function logout() {
        $this->imEngine->logout();
    }

    public function loadSigner() {
        [$signer, $signerList] = $this->imEngine->loadSigner();
        $this->response($signer ?: null);
        if ($signerList) {
            $this->response($signerList, 'project/im/login');
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