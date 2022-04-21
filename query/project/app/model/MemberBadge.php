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
class MemberBadge extends DbActiveRecord {
    #[ Property, DbField(FieldType::Tinyint, primary: true, increment: true), ]
    public int $id;

    #[ Property('徽章名'), DbField(FieldType::Varchar, 15),
        Validator(Validator::RULE_STRING, max: 15),
        Validator(Validator::RULE_REQUIRED),
    ]
    public string $name;

    #[ Property('备注'), DbField(FieldType::Varchar), Validator(Validator::RULE_STRING, max: 31), ]
    public string $memo;

    #[ Property('添加时间'), DbField(FieldType::Datetime), Validator(Validator::RULE_DATETIME), ]
    public string $createTime;
}
