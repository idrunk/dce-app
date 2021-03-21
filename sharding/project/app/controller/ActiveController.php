<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 18:41
 */

namespace app\controller;

use app\model\Member;
use app\model\MemberBadge;
use app\model\MemberBadgeMap;
use app\model\MemberSignIn;
use dce\project\node\Node;
use dce\project\view\ViewCli;

class ActiveController extends ViewCli {
    // 需在协程中运行
    #[Node('active', enableCoroutine: true, controllerPath: true)]
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

        for ($i = 1000; $i --;) {
            $member = new Member();
            $member->username = ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)];
            $member->password = sha1($member->username);
            $member->mobile = sprintf('1%s', rand(3000000000, 9999999999));
            $member->nickname = ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)];
            $member->gender = rand(0, 2);
            $member->signUpIp = $this->rawRequest->getClientInfo()['ip'] ?? '';
            $member->signUpTime = date('Y-m-d H:i:s');
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
                $memberSignIn = new MemberSignIn();
                $memberSignIn->mid = $member->mid;
                $memberSignIn->type = rand(0, 5);
                $memberSignIn->signInDate = date('Y-m-d', rand(1400000000, 1700000000));
                $memberSignIn->lastSignInDate = date('Y-m-d', rand(1400000000, 1700000000));
                $memberSignIn->createTime = date('Y-m-d H:i:s');
                $memberSignIn->save();
            }
        }

        $this->print('数据插入完成!');
    }

    #[Node]
    public function select() {
        $this->print("用户列表:\n\n");
        $allMembers = Member::query()->with('badge', 'sign_in')->order('mid')->limit(10)->select();
        foreach ($allMembers as $member) {
            $this->print($member->extractProperties());
            $this->print($member->badge ?? null);
            $this->print($member->signIn ?? null, "\n\n");
        }

        $this->print("\n用户详情:\n\n");
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]->mid;
        $member = Member::find($randMid);
        $this->print($member->extractProperties());
        $this->print($member->badge);
        $this->print($member->signIn ? $member->signIn->extractProperties() : null);
    }

    #[Node]
    public function update() {
        $allMembers = Member::query()->limit(10)->select();
        $randMember = Member::find($allMembers[rand(0, count($allMembers) - 1)]->mid);
        $this->printf("更新前:\n\n%s\n\n", $randMember->extractProperties());

        $randMember->username = ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)];
        $randMember->password = sha1($randMember->username);
        $randMember->mobile = sprintf('1%s', rand(3000000000, 9999999999));
        $randMember->nickname = ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)];
        $randMember->gender = rand(0, 2);
        $randMember->signUpIp = $this->rawRequest->getClientInfo()['ip'] ?? '';
        $randMember->signUpTime = date('Y-m-d H:i:s');
        $randMember->save();
        $this->printf("更新后:\n\n%s\n\n", $randMember->extractProperties());
    }

    #[Node]
    public function delete() {
        $allMembers = Member::query()->limit(10)->select();
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]->mid;
        $randMember = Member::find($randMid);

        $this->printf("删除前:\n\n%s\n\n", $randMember->extractProperties());
        $randMember->delete();
        $randMember = Member::find($randMid);
        $this->printf("删除后:\n\n%s\n\n", $randMember);
    }
}