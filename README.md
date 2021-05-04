# Dce使用示例

本项目中Cgi示例在Windows的IIS部署测试通过，也支持其他各种Cgi部署，如Nginx、Apache。非Cgi的通用编程示例，作者在Windows下的命令行或者git-bash中测试，其他全部通过Windows WSL2 Ubuntu Podman测试，也建议你使用Podman或者Docker测试，比较简单便捷。

## 通用编程

### [传统Cgi网页](./cgi/)

### [命令行编程](./cli/)

### [模型、校验器、缓存器、事件](./model/)

### [查询器、活动记录](./query/)

### [库模式编程（不引导路由Dce节点执行控制器）](./lib/)

### [国际化、类装饰器](./i18n/)

## Swoole编程

需在Linux下的装有Swoole扩展的PHP环境测试使用，推荐使用Podman或Docker。

### [常驻内存网页](./http/)

### [远程过程调用（RPC）、ID生成器、负载均衡连接池、并发锁](./rpc/)

### [分库查询器](./sharding/)

### [Websocket即时通信](./websocket/)

### [分布式Websocket即时通信及SessionManager应用](./session/)
