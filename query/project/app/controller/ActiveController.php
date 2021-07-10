<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 18:41
 */

namespace app\controller;

use app\model\Member;
use app\model\MemberBadge;
use app\model\MemberBadgeMap;
use app\model\MemberLogin;
use dce\Dce;
use dce\project\Controller;
use dce\project\node\Node;

class ActiveController extends Controller {
    #[Node('active', controllerPath: true)]
    public function __() {}

    #[Node]
    public function insert() {
        for ($i = 3; $i --;) {
            $badgeName = ['daughter', 'angle', 'tumble', 'bat', 'ear'][rand(0, 4)];
            if (! MemberBadge::find(['name', $badgeName])) {
                $memberBadge = new MemberBadge();
                $memberBadge->name = $badgeName;
                $memberBadge->createTime = date('Y-m-d H:i:s');
                $memberBadge->save();
            }
        }

        $badges = MemberBadge::query()->select();

        for ($i = 5; $i --;) {
            $member = new Member();
            $member->mid = Dce::$config->idGenerator->generate('mid');
            $member->username = ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)];
            $member->password = sha1($member->username);
            $member->mobile = sprintf('1%s', rand(3000000000, 9999999999));
            $member->nickname = ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)];
            $member->gender = rand(0, 2);
            $member->registerIp = $this->rawRequest->getClientInfo()['ip'] ?? '';
            $member->registerTime = date('Y-m-d H:i:s');
            $member->save();

            for ($j = rand(0, 3); $j --;) {
                $mbId = $badges[rand(0, count($badges) - 1)]->id;
                if (! MemberBadgeMap::find([['mid', $member->mid], ['mb_id', $mbId]])) {
                    $memberBadgeMap = new MemberBadgeMap();
                    $memberBadgeMap->mid = $member->mid;
                    $memberBadgeMap->mbId = $mbId;
                    $memberBadgeMap->insert();
                }
            }

            for ($j = rand(0, 4); $j --;) {
                $memberLogin = new MemberLogin();
                $memberLogin->id = Dce::$config->idGenerator->generate('msi_id');
                $memberLogin->mid = $member->mid;
                $memberLogin->type = rand(0, 5);
                $memberLogin->loginDate = date('Y-m-d', rand(1400000000, 1700000000));
                $memberLogin->lastLoginDate = date('Y-m-d', rand(1400000000, 1700000000));
                $memberLogin->createTime = date('Y-m-d H:i:s');
                $memberLogin->save();
            }
        }

        $this->print('数据插入成功!');
    }

    #[Node]
    public function select() {
        $this->print("用户列表:\n\n");
        $allMembers = Member::query()->with('Badge', 'Login')->select();
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]->mid;
        foreach ($allMembers as $member) {
            $this->print($member->extractProperties());
            $this->print($member->badge ?? null);
            $this->print($member->login ?? null, "\n\n");
        }

        $this->print("\n用户详情:\n\n");
        $member = Member::find($randMid);
        $this->print($member->extractProperties());
        $this->print($member->badge);
        $this->print($member->login ? $member->login->extractProperties() : null);
    }

    #[Node]
    public function update() {
        $allMembers = Member::query()->select();
        $randMember = Member::find($allMembers[rand(0, count($allMembers) - 1)]->mid);
        $this->printf("更新前:\n\n%s\n\n", $randMember->extractProperties());

        $randMember->username = ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)];
        $randMember->password = sha1($randMember->username);
        $randMember->mobile = sprintf('1%s', rand(3000000000, 9999999999));
        $randMember->nickname = ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)];
        $randMember->gender = rand(0, 2);
        $randMember->registerIp = $this->rawRequest->getClientInfo()['ip'] ?? '';
        $randMember->registerTime = date('Y-m-d H:i:s');
        $randMember->save();
        $this->printf("更新后:\n\n%s\n\n", $randMember->extractProperties());
    }

    #[Node]
    public function delete() {
        $allMembers = Member::query()->select();
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]->mid;
        $randMember = Member::find($randMid);

        $this->printf("删除前:\n\n%s\n\n", $randMember->extractProperties());
        $randMember->delete();
        $randMember = Member::find($randMid);
        $this->printf("删除后:\n\n%s\n\n", $randMember);
    }
}