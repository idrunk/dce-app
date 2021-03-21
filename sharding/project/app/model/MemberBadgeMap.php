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
class MemberBadgeMap extends DbActiveRecord {
    #[ Property('会员ID'), DbField(FieldType::BIGINT), Validator(Validator::RULE_REQUIRED), ]
    public int $mid;

    #[ Property('徽章ID'), DbField(FieldType::TINYINT), Validator(Validator::RULE_REQUIRED), ]
    public int $mbId;
}
