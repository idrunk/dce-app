<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-14 02:05
 */

namespace rpc\app\service;

use app\model\TestModel;
use dce\rpc\RpcMatrix;

class TestService extends RpcMatrix {
    public static function counter(TestModel $model): TestModel {
        $model->counter ++;
        return $model;
    }
}