<?php
/**
 * Author: Drunk (drunkce.com; idrunk.net)
 * Date: 2019/9/15 22:31
 */

namespace app\model;

use dce\db\active\ActiveRelation;
use dce\db\active\DbActiveRecord;
use dce\db\entity\DbField;
use dce\db\entity\schema\FieldType;
use dce\model\Property;
use dce\model\Validator;

/**
 * Class Member
 * @package tests\model
 * @property MemberBadge[] $badge
 * @property MemberLogin $login
 */
class Member extends DbActiveRecord {
    public const SCENARIO_REGISTER = 'register';

    #[ Property, DbField(FieldType::BIGINT, primary: true), ]
    public int $mid;

    #[ Property('用户名'), DbField(FieldType::VARCHAR, 16),
        Validator(Validator::RULE_STRING, [self::SCENARIO_DEFAULT, self::SCENARIO_REGISTER], 16, 3),
        Validator(Validator::RULE_REQUIRED, [self::SCENARIO_REGISTER]),
    ]
    public string $username;

    #[ Property('密码'), DbField(FieldType::VARCHAR, 128), ]
    public string $password;

    #[ Property('手机'), DbField(FieldType::VARCHAR, 11),
        Validator(Validator::RULE_REGULAR, [self::SCENARIO_DEFAULT, self::SCENARIO_REGISTER], regexp: '/^1[3-9]\d{9}$/'),
        Validator(Validator::RULE_REQUIRED, [self::SCENARIO_REGISTER]),
    ]
    public string $mobile;

    #[ Property('昵称'), DbField(FieldType::VARCHAR, 16, ''), Validator(Validator::RULE_STRING, max: 16, min: 2), ]
    public string $nickname;

    #[ Property('头像'), DbField(FieldType::VARCHAR, 128, ''), Validator(Validator::RULE_STRING, max: 128), ]
    public string $avatar;

    #[ Property('姓名'), DbField(FieldType::VARCHAR, 16, ''), Validator(Validator::RULE_STRING, max: 16), ]
    public string $name;

    #[ Property('性别'), DbField(FieldType::TINYINT, default: 0), Validator(Validator::RULE_SET, set: [0, 1, 2]), ]
    public int $gender;

    #[ Property('注册IP'), DbField(FieldType::VARCHAR, 15, ''), Validator(Validator::RULE_IP), ]
    public string $registerIp;

    #[ Property('注册时间'), DbField(FieldType::DATETIME, default: '1900-01-01'), Validator(Validator::RULE_DATETIME), ]
    public string $registerTime;

    public function getBadgeMap(): ActiveRelation {
        return $this->hasMany(MemberBadgeMap::class, ['mid' => 'mid']);
    }

    public function getBadge(): ActiveRelation {
        return $this->hasMany(MemberBadge::class, ['id' => 'mb_id'], 'badgeMap');
    }

    public function getLogin(): ActiveRelation {
        $relation = $this->hasOne(MemberLogin::class, ['mid' => 'mid']);
        $relation->getActiveQuery()->limit(1);
        return $relation;
    }
}
