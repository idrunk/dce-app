<?php
/**
 * Author: Drunk
 * Date: 2020-04-27 14:35
 */

namespace sample\service;

use dce\project\ProjectManager;
use dce\project\session\SessionManager;
use Swoole\Table;
use websocket\service\WebsocketServer;

class ImWebsocketService {
    private const PATH_USER_LIST = 'setUserList';

    private const PATH_MESSAGE_LIST = 'setMessageList';

    private int $fd;

    private WebsocketServer $server;

    public function __construct(WebsocketServer $websocketServer, int $fd) {
        $this->fd = $fd;
        $this->server = $websocketServer;
    }

    /**
     * 登录
     * @param string $nickname
     * @param string $brief
     */
    public function signIn(string $nickname, string $brief) {
        $mid = $this->signUp($nickname, $brief);
        SessionManager::inst()->fdSignIn($mid, $this->fd, $this->server->apiHost, $this->server->apiPort);
        // 登录成功后通知所有用户刷新在线用户列表
        $this->notifyUserList();
        // 给登录用户推送历史消息列表
        $this->pushMessageList();
    }

    public function signUp(string $nickname, string $brief) : int {
        foreach ($this->getMemberTable() as $log) {
            // 如果有同名用户了, 则视为已登录, 直接返回其ID
            if ($log['nickname'] === $nickname) {
                return $log['id'];
            }
        }
        $id = $this->fd;
        $this->getMemberTable()->set($id, [
            'id' => $id,
            'nickname' => $nickname,
            'brief' => $brief,
        ]);
        return $id;
    }

    /**
     * 通知全部在线用户刷新用户列表
     */
    private function notifyUserList() {
        // 连接列表
        $fdList = [];
        // 用户列表 (连接不等于用户, 因为一个用户可以建立多个连接, 多处登录)
        $memberList = [];
        // 取全部连接
        foreach (SessionManager::inst()->listFdForm() as $fdForm) {
            // 仅取其中登录了的
            if ($mid = SessionManager::inst()->getSessionForm($fdForm['sid'])) {
//                testPoint($mid, $fdForm, $this->getMemberTable()->get($mid));
                $fdList[] = $fdForm;
                $memberList[$mid] = $this->getMemberTable()->get($mid);
            }
        }
        $memberList = array_values($memberList);
        foreach ($fdList as ['fd' => $fd, 'host' => $host, 'port' => $port]) {
            // 单服务器可以这么用, 如果是多台的分布式的服务器, 则不对, 因为用户连接可能在别的服务器, 你无法直接向那台服务器连接发消息
            // 你可以编写服务器接口, 用以服务器建收发消息, 实现跨服务器向客户端发消息. Dce目前未提供直接操作的方法, 后续会提供.
            // 另外Dce作者建议你利用MQ实现服务器间消息收发, 可以减少浪费服务器连接占用
            $this->server->push($fd, $memberList, self::PATH_USER_LIST);
        }
    }

    /**
     * 推送消息列表
     */
    private function pushMessageList() {
        $messageList = [];
        $messageIdList = [];
        foreach ($this->getMessageTable() as $id => $message) {
            $messageIdList[] = $id;
            $message['nickname'] = $this->getMemberTable()->get($message['mid'])['nickname'];
            $messageList[] = $message;
        }
        array_multisort($messageIdList, SORT_NUMERIC, SORT_ASC, $messageList);
        $this->server->push($this->fd, $messageList, self::PATH_MESSAGE_LIST);
    }

    /**
     * 通知全部在线用户接收新消息
     * @param array $message
     */
    private function notifyNewMessage(array $message) {
        $message['nickname'] = $this->getMemberTable()->get($message['mid'])['nickname'];
        $messageList = [$message];
        // 遍历连接集
        foreach (SessionManager::inst()->listFdForm() as ['sid' => $sid, 'fd' => $fd]) {
            // 判断sid是否已登录
            if (SessionManager::inst()->getSessionForm($sid)) {
                $this->server->push($fd, $messageList, self::PATH_MESSAGE_LIST);
            }
        }
    }

    /**
     * 发送消息
     * @param string $messageContent
     */
    public function sendMessage(string $messageContent) {
        // 根据fd找到对应的登录mid
        $mid = SessionManager::inst()->getSessionForm(SessionManager::inst()->getFdForm($this->fd, $this->server->apiHost, $this->server->apiPort)['sid']);
        $id = $this->getMessageTable()->count() + 1;
        $message = [
            'id' => $id,
            'mid' => $mid,
            'message' => $messageContent,
            'create_time' => date('Y-m-d H:i:s'),
        ];
        $this->getMessageTable()->set($id, $message);
        $this->notifyNewMessage($message);
    }

    /**
     * 退出
     */
    public function signOut() {
        $sid = SessionManager::inst()->getFdForm($this->fd, $this->server->apiHost, $this->server->apiPort)['sid'] ?? 0;
        // 在close事件里面调用了此方法, 因为不是所有的close都是正常websocket连接, 所以不会有FdForm, 也就不会有sid, 所以这种情况不该进入退出逻辑
        if ($sid) {
            SessionManager::inst()->signOut($sid);
            // 退出后通知所有用户刷新在线用户列表
            $this->notifyUserList();
        }
    }

    /**
     * 取fd用户映射表
     * @return Table
     */
    private function getMemberTable() : Table {
        return ProjectManager::get('websocket')->extra['memberTable'];
    }

    /**
     * 取消息表
     * @return Table
     */
    private function getMessageTable() : Table {
        return ProjectManager::get('websocket')->extra['messageTable'];
    }
}
