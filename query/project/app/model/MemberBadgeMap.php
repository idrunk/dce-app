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
class MemberBadgeMap extends DbActiveRecord {
    #[ Property('会员ID'), DbField(FieldType::Bigint), Validator(Validator::RULE_REQUIRED), ]
    public int $mid;

    #[ Property('徽章ID'), DbField(FieldType::Tinyint), Validator(Validator::RULE_REQUIRED), ]
    public int $mbId;
}
