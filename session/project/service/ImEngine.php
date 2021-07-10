<?php
namespace project\service;

use dce\cache\engine\FileCache;
use dce\Dce;
use dce\project\request\Request;
use dce\project\session\SessionManager;
use drunk\Structure;
use websocket\service\WebsocketServer;

class ImEngine {
    private WebsocketServer $server;

    private FileCache $cache;

    public function __construct(
        private Request $request,
    ) {
        $this->server = $this->request->rawRequest->getServer();
        $this->cache = Dce::$cache->file;
    }

    public function login(string $nickname, string $brief): array {
        $nickname = mb_substr($nickname, 0, 16);
        $brief = mb_substr($brief, 0, 128);
        $memberList = $this->cache->get('members') ?: [];
        $mid = Structure::arraySearch(['nickname' => $nickname], $memberList) ?: count($memberList) + 1;
        $memberInfo = $memberList[$mid] = ['mid' => $mid, 'nickname' => $nickname, 'brief' => $brief];
        $this->cache->set('members', $memberList);

        // SessionManager标记登录
        SessionManager::inst()->fdLogin($mid, $this->request->fd, $this->server->apiHost, $this->server->apiPort);
        // 设置/更新当前登录者的全部Session存的用户信息
        SessionManager::inst()->setSession($mid, 'signer', $memberInfo);
        // 登录后刷新登录用户列表
        $this->refreshOnlineMember();
        return $memberInfo;
    }

    public function logout(): void {
        // 删除当前Session里的登录用户信息
        $this->request->session->delete('signer');
        // SessionManager标记当前sid的用户已退出登录
        SessionManager::inst()->logout($this->request->session->getId());
        // 退出时刷新登录用户列表
        $this->refreshOnlineMember();
    }

    private function refreshOnlineMember() {
        $signerList = $this->listSigner();
        $fdFormList = SessionManager::inst()->listFdForm(limit: null);
        foreach ($fdFormList as $fdid => $fdForm) {
            // 向全部在线用户(不一定已登录)推送在线登录用户列表
            SessionManager::inst()->sendMessageFd($fdid, $signerList, 'project/im/login');
        }
    }

    public function loadSigner(): array|false {
        $signerInfo = $this->request->session->get('signer');
        if ($signerInfo) {
            // 加载仅在连接建立时调用, 建立连接时可能会对新fd已老sid自动登录, 此动作类似登录, 可以刷新一下在线列表
            $this->refreshOnlineMember();
        } else {
            $signerList = $this->listSigner();
        }
        return [$signerInfo, $signerList ?? null];
    }

    private function listSigner(): array {
        $fdFormList = SessionManager::inst()->listFdForm(limit: null);
        $memberList = $this->cache->get('members') ?: [];
        $signerList = [];
        foreach ($fdFormList as $fdForm) {
            if (! $mid = SessionManager::inst()->getSessionForm($fdForm['sid'])) {
                // 仅筛选已登录的
                continue;
            }
            if (key_exists($mid, $memberList)) {
                if (key_exists($mid, $signerList)) {
                    $signerList[$mid]['loginPlaces'] ++;
                } else {
                    $signerList[$mid] = $memberList[$mid] + ['loginPlaces' => 1];
                }
            }
        }
        return array_values($signerList);
    }

    public function sendMessage(string $message, int $targetMid): void {
        $messageList = $this->cache->get('messages') ?: [];
        $messageList[] = $messageInfo = [
            'id' => count($messageList) + 1,
            'mid' => $this->request->session->get('signer')['mid'],
            'target_mid' => $targetMid,
            'message' => $message,
            'create_time' => date('Y-m-d H:i:s'),
        ];
        $this->cache->set('messages', $messageList);

        // 新消息通知
        $newMessageList = $this->fillMessage([$messageInfo]);
        if ($targetMid > 0) {
            $mid = $this->request->session->get('signer')['mid'] ?? 0;
            SessionManager::inst()->sendMessage($mid, $newMessageList, 'project/im/load');
            SessionManager::inst()->sendMessage($targetMid, $newMessageList, 'project/im/load');
        } else {
            $fdFormList = SessionManager::inst()->listFdForm(limit: null);
            foreach ($fdFormList as $fdid => $fdForm) {
                // 向全部在线用户(不一定已登录)推送新消息列表
                SessionManager::inst()->sendMessageFd($fdid, $newMessageList, 'project/im/load');
            }
        }
    }

    public function loadMessage(int $targetMid): array {
        $mid = $this->request->session->get('signer')['mid'] ?? 0;
        $messageList = array_filter($this->cache->get('messages') ?: [], fn(array $message) => $targetMid > 0
            ? ($message['mid'] === $mid && $message['target_mid'] === $targetMid) || ($message['mid'] === $targetMid && $message['target_mid'] === $mid)
            : $message['target_mid'] === 0
        );
        $messageList = $this->fillMessage($messageList);
        return $messageList;
    }

    private function fillMessage(array $messageList): array {
        foreach ($messageList as $k => $message) {
            $message['nickname'] = $this->cache->get('members')[$message['mid']]['nickname'] ?? '';
            $messageList[$k] = $message;
        }
        return $messageList;
    }
}