<?php
namespace model;

use dce\model\Model;
use dce\model\Property;
use dce\model\Validator;

class TestModel extends Model {
    #[Property, Validator(Validator::RULE_INTEGER, max: 100, min: [10, ['不能小于10啊', 'Cannot less than 10'], 10087])]
    public int $id;

    #[Property, Validator(Validator::RULE_INTEGER, max: 100, error: [0, ['非有效整数', 'Not integer'], 10088])]
    public int $id2;
}