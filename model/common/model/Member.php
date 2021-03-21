<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 16:20
 */

namespace model;

use dce\Dce;
use dce\model\Model;
use dce\model\Property;
use dce\model\Validator;

class Member extends Model {
    #[Property('昵称'), Validator(Validator::RULE_REQUIRED), Validator(Validator::RULE_STRING, max: 16, min: 2)]
    public string $nickname;

    #[Property]
    public string $brief;

    public function save(): bool {
        $this->valid();
        return Dce::$cache->file->set($this->nickname, $this);
    }

    public static function find(string $nickname): self|false {
        return Dce::$cache->file->get($nickname);
    }
}