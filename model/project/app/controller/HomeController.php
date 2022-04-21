<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 16:15
 */

namespace app\controller;

use dce\project\Controller;
use service\LoginService;
use dce\project\node\Node;

class HomeController extends Controller {
    #[Node('app', 'cli', omissiblePath: true)]
    public function __init(): void {}

    #[Node('login')]
    public function login() {
        $service = new LoginService();
        $nickname = $this->input('请输入昵称: ');
        $member = $service->login($nickname);
        if (! $member) {
            $brief = $this->input('用户不存在, 请输入简介完成注册: ');
            $service->register($nickname, $brief);
            $member = $service->login($nickname);
        }
        if ($member) {
            $this->print('登录成功!');
            $this->print('你的用户信息: ', json_encode($member->extract(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        } else {
            $this->print('登录失败!');
        }
    }

    #[Node('signer', extra: ['auth' => 1])]
    public function signer() {
        // 进不到这里, 因为在 \service\AuthService 中被拦截了
        $this->print("你, 进不来");
    }
}