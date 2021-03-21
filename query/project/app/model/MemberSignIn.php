<?php
/**
 * Author: Drunk (drunkce.com; idrunk.net)
 * Date: 2019/9/15 22:31
 */

namespace app\model;

use dce\db\active\DbActiveRecord;
use dce\db\entity\DbField;
use dce\db\entity\schema\FieldType;
use dce\model\Property;
use dce\model\Validator;

/**
 * @package tests\model
 */
class MemberSignIn extends DbActiveRecord {
    #[ Property('ID'), DbField(FieldType::BIGINT), ]
    public int $id;

    #[ Property('会员ID'), DbField(FieldType::BIGINT), Validator(Validator::RULE_INTEGER), Validator(Validator::RULE_REQUIRED), ]
    public int $mid;

    #[ Property('记录类型'), DbField(FieldType::TINYINT), Validator(Validator::RULE_INTEGER), ]
    public int $type;

    #[ Property('登录终端'), DbField(FieldType::TINYINT), Validator(Validator::RULE_INTEGER), ]
    public int $terminal;

    #[ Property('登录日期'), DbField(FieldType::DATE), Validator(Validator::RULE_DATE), Validator(Validator::RULE_REQUIRED), ]
    public string $signInDate;

    #[ Property('上次登录日期'), DbField(FieldType::DATE), Validator(Validator::RULE_DATE), ]
    public string $lastSignInDate;

    #[ Property('添加时间'), DbField(FieldType::DATETIME), Validator(Validator::RULE_DATETIME), ]
    public string $createTime;
}
