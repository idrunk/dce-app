<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 18:41
 */

namespace app\controller;

use dce\Dce;
use dce\project\Controller;
use dce\project\node\Node;

class QueryController extends Controller {
    #[
        Node('app', 'cli', omissiblePath: true),
        Node('query', controllerPath: true),
    ]
    public function __() {}

    #[Node]
    public function insert() {
        for ($i = 3; $i --;) {
            $badgeName = ['daughter', 'angle', 'tumble', 'bat', 'ear'][rand(0, 4)];
            if (! db('member_badge')->where('name', $badgeName)->exists()) {
                db('member_badge')->insert([
                    'name' => $badgeName,
                    'create_time' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $badges = db('member_badge')->select();

        for ($i = 5; $i --;) {
            $mid = Dce::$config->idGenerator->generate('mid');
            db('member')->insert([
                'mid' => $mid,
                'username' => ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)],
                'password' => sha1(['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)]),
                'mobile' => sprintf('1%s', rand(3000000000, 9999999999)),
                'nickname' => ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)],
                'gender' => rand(0, 2),
                'sign_up_ip' => $this->rawRequest->getClientInfo()['ip'] ?? '',
                'sign_up_time' => date('Y-m-d H:i:s'),
            ]);

            for ($j = rand(0, 3); $j --;) {
                $mbId = $badges[rand(0, count($badges) - 1)]['id'];
                if (! db('member_badge_map')->where([['mid', $mid], ['mb_id', $mbId]])->exists()) {
                    db('member_badge_map')->insert([
                        'mid' => $mid,
                        'mb_id' => $mbId,
                    ]);
                }
            }

            for ($j = rand(0, 4); $j --;) {
                db('member_sign_in')->insert([
                    'id' => Dce::$config->idGenerator->generate('msi_id'),
                    'mid' => $mid,
                    'type' => rand(0, 5),
                    'sign_in_date' => date('Y-m-d', rand(1400000000, 1700000000)),
                    'last_sign_in_date' => date('Y-m-d', rand(1400000000, 1700000000)),
                    'create_time' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->print('数据插入成功!');
    }

    #[Node]
    public function select() {
        $this->print("用户列表:\n\n");
        $allMembers = db('member')->select();
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]['mid'];
        foreach ($allMembers as $member) {
            $this->print($member);
            $this->print(db('member_badge', 'mb')->join('member_badge_map', 'mbm', 'mbm.mb_id = mb.id')
                ->where('mbm.mid', $member['mid'])->select('mb.*'));
            $this->print(db('member_sign_in')->where('mid', $member['mid'])->find(), "\n\n");
        }

        $this->print("\n用户详情:\n\n");
        $member = db('member')->where('mid', $randMid)->find();
        $this->print($member);
        $this->print(db('member_badge', 'mb')->join('member_badge_map', 'mbm', 'mbm.mb_id = mb.id')
            ->where('mbm.mid', $member['mid'])->select('mb.*'));
        $this->print(db('member_sign_in')->where('mid', $member['mid'])->find());
    }

    #[Node]
    public function update() {
        $allMembers = db('member')->select();
        $randMember = db('member')->where('mid', $allMembers[rand(0, count($allMembers) - 1)]['mid'])->find();
        $this->printf("更新前:\n\n%s\n\n", $randMember);

        db('member')->where('mid', $randMember['mid'])->update([
            'username' => ['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)],
            'password' => sha1(['apple', 'mango', 'papaya', 'banana', 'guava', 'pineapple'][rand(0, 5)]),
            'mobile' => sprintf('1%s', rand(3000000000, 9999999999)),
            'nickname' => ['football', 'obligation', 'problem', 'data', 'anxiety', 'sir'][rand(0, 5)],
            'gender' => rand(0, 2),
            'sign_up_ip' => $this->rawRequest->getClientInfo()['ip'] ?? '',
            'sign_up_time' => date('Y-m-d H:i:s'),
        ]);
        $this->printf("更新后:\n\n%s\n\n", db('member')->where('mid', $randMember['mid'])->find());
    }

    #[Node]
    public function delete() {
        $allMembers = db('member')->select();
        $randMid = $allMembers[rand(0, count($allMembers) - 1)]['mid'];
        $randMember = db('member')->where('mid', $randMid)->find();
        $this->printf("删除前:\n\n%s\n\n", $randMember);

        db('member')->where('mid', $randMid)->delete();
        $randMember = db('member')->where('mid', $randMid)->find();
        $this->printf("删除后:\n\n%s\n\n", $randMember);
    }
}