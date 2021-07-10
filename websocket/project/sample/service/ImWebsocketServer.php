<?php
/**
 * Author: Drunk
 * Date: 2020-04-27 19:30
 */

namespace sample\service;

use dce\project\ProjectManager;
use Swoole\Table;
use websocket\service\WebsocketServer;

class ImWebsocketServer extends WebsocketServer {
    protected function eventBeforeStart($server): void {
        // 新建用户表
        $memberTable = new Table(128);
        $memberTable->column('id', Table::TYPE_INT);
        $memberTable->column('nickname', Table::TYPE_STRING, 48);
        $memberTable->column('brief', Table::TYPE_STRING, 255);
        $memberTable->create();

        // 新建消息表
        $messageTable = new Table(1024);
        $messageTable->column('id', Table::TYPE_INT);
        $messageTable->column('mid', Table::TYPE_INT);
        $messageTable->column('message', Table::TYPE_STRING, 1024);
        $messageTable->column('create_time', Table::TYPE_STRING, 32);
        $messageTable->create();

        $project = ProjectManager::get('websocket');
        // 将共享内存表绑定到项目扩展属性
        $project->extendProperty('memberTable', $memberTable);
        $project->extendProperty('messageTable', $messageTable);

        /*
         * 本方法是为了方便示例而建了共享内存表, 共享内存表需在服务器开启之前建立并绑定, 这样服务器启动后才能正常操控共享内存
         * 实际应用中你的数据表应该是存于Mysql等数据库中的, 所以不需要以上操作
         */
    }

    protected function eventOnClose($server, int $fd, int $reactorId): void {
        $imService = new ImWebsocketService($this, $fd);
        // 断开连接时退出登录 (如果你不需要断开即主动退出则无需此处理)
        $imService->logout();
    }
}
