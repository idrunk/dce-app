<?php
/**
 * Author: Drunk (drunkce.com; idrunk.net)
 * Date: 2019/9/15 22:31
 */

namespace app\model;

use dce\db\active\DbActiveRecord;
use dce\db\entity\DbField;
use dce\db\entity\FieldType;
use dce\model\Property;
use dce\model\Validator;

/**
 * @package tests\model
 */
class MemberLogin extends DbActiveRecord {
    #[ Property('ID'), DbField(FieldType::Bigint), ]
    public int $id;

    #[ Property('会员ID'), DbField(FieldType::Bigint), Validator(Validator::RULE_INTEGER), Validator(Validator::RULE_REQUIRED), ]
    public int $mid;

    #[ Property('记录类型'), DbField(FieldType::Tinyint), Validator(Validator::RULE_INTEGER), ]
    public int $type;

    #[ Property('登录终端'), DbField(FieldType::Tinyint), Validator(Validator::RULE_INTEGER), ]
    public int $terminal;

    #[ Property('登录日期'), DbField(FieldType::Date), Validator(Validator::RULE_DATE), Validator(Validator::RULE_REQUIRED), ]
    public string $loginDate;

    #[ Property('上次登录日期'), DbField(FieldType::Date), Validator(Validator::RULE_DATE), ]
    public string $lastLoginDate;

    #[ Property('添加时间'), DbField(FieldType::Datetime), Validator(Validator::RULE_DATETIME), ]
    public string $createTime;
}
