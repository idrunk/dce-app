<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 01:53
 */

namespace app\controller;

use app\model\TestModel;
use dce\base\SwooleUtility;
use dce\Dce;
use dce\project\Controller;
use dce\project\node\Node;
use dce\rpc\RpcClient;
use dce\rpc\RpcHost;
use dce\rpc\RpcServer;
use dce\storage\redis\RedisConnector;
use dce\storage\redis\RedisPool;
use rpc\app\service\TestService;
use Swoole\Coroutine;

class HomeController extends Controller {
    #[Node('app', 'cli', omissiblePath: true)]
    public function __init(): void {}

    #[Node('rpc')]
    public function rpc() {
        // 开启远程服务器 (在这里是一个子进程), 若在远程服务器中无法自动加载 \rpc\app\service\TestService 类, 则通过preload/prepare手动加载
        $server = RpcServer::new(new RpcHost(['host' => '/var/run/test_rpc.sock', 'port' => 0]))
            ->preload($this->request->project->path . 'service/TestService.php')->start();

        // 注册 \rpc\app\service\TestService 为远程类, 将方法转发到远程处理
        RpcClient::preload('\rpc\app\service\TestService', ['host' => '/var/run/test_rpc.sock', 'port' => 0]);

        // 远程请求会用到Tcp连接池, 连接池需要协程环境运行
        Coroutine\run(function () /*use($server)*/ {
            $model = new TestModel();
            // \rpc\app\service\TestService 类不符合自动加载策略, 所以无法自动加载, 会转发到上面注册的远程服务器处理, 再返回结果
            // 你可以尝试将开启RPC服务器的代码, 或者客户端注册远程服务的代码注释掉, 此处的代码将无法再正常执行
            testPoint($model = TestService::counter($model));
            testPoint(TestService::counter($model));

            // 暂时无法正常关闭, 一般情况下你无需关闭, 或者可以手动kill进程/关闭容器
            // $server->stop();
            // 可以这样关闭, 但不推荐
            die;
        });
    }

    #[Node('idg', enableCoroutine: true, hookCoroutine: true)]
    public function idg() {
        for ($i=10000; $i--;) {
            testStep(Dce::$config->idGenerator->generate('mid'));
        }
    }

    #[Node('pool', enableCoroutine: true, hookCoroutine: true)]
    public function pool() {
        $mapping = [];
        $barrier = Coroutine\Barrier::make();
        for ($i=100; $i--;) {
            go(function () use(& $mapping, $barrier) {
                $redis = RedisPool::inst()->setConfigs(Dce::$config->redis)->fetch();
                $objId = spl_object_id($redis);
                $mapping[$objId] = ($mapping[$objId] ?? 0) + 1;
                Coroutine::sleep(0.01);
                RedisPool::inst()->put($redis);
            });
        }
        Coroutine\Barrier::wait($barrier);
        // 可以看到实例化了16个Redis对象, 他们被使用了6-7次, 实现了负载均衡
        testPoint($mapping);
    }

    #[Node('lock', enableCoroutine: true)]
    public function lock() {
        testPoint();
        $barrier = Coroutine\Barrier::make();
        for ($i=100; $i--;) {
            go(function () use($barrier) {
                Dce::$lock->coLock('lock1');
                Coroutine::sleep(0.1);
                Dce::$lock->coUnlock('lock1');
            });
        }
        Coroutine\Barrier::wait($barrier);
        // 若注释掉锁, 会并行执行, 步耗仅0.1s, 加锁后, 锁定部分会排队等待执行, 需10s步耗
        testPoint();
    }
}