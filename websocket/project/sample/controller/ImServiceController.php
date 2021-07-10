<?php
/**
 * Author: Drunk
 * Date: 2017-3-24 19:23
 */

namespace sample\controller;

use dce\project\Controller;
use sample\service\ImWebsocketService;

class ImServiceController extends Controller {
    private ImWebsocketService $imService;

    public function __init(): void {
        $this->imService = new ImWebsocketService($this->rawRequest->getServer(), $this->request->fd);
    }

    public function login() {
        $nickname = $this->request->request['nickname'];
        $brief = $this->request->request['brief'];
        $this->imService->login($nickname, $brief);
    }

    public function logout() {
        $this->imService->logout();
    }

    public function send() {
        $this->imService->sendMessage($this->request->request['message']);
    }
}
