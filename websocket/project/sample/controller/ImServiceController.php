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

    public function signIn() {
        $nickname = $this->request->request['nickname'];
        $brief = $this->request->request['brief'];
        $this->imService->signIn($nickname, $brief);
    }

    public function signOut() {
        $this->imService->signOut();
    }

    public function send() {
        $this->imService->sendMessage($this->request->request['message']);
    }
}
