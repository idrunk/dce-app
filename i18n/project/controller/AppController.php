<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-04-30 14:03
 */

namespace project\controller;

use dce\i18n\Language;
use dce\i18n\Locale;
use dce\loader\ClassDecorator;
use dce\loader\StaticInstance;
use dce\project\Controller;
use dce\project\node\Node;
use model\TestModel;
use service\LangException;
use Throwable;

// 实现 ClassDecorator 接口以支持类静态属性自动实例化
class AppController extends Controller implements ClassDecorator {
    #[Node('project', 'cli', omissiblePath: true)]
    public function __init(): void {}

    #[Node]
    public function locale() {
        testPoint(
            Locale::client(), // 取当前请求的本地化环境
            Locale::server(), // 取当前服务器本地化环境
        );
    }

    #[Node]
    public function lang() {
        $lang = lang(['你好 %s !', 'Hello %s !'], 10000);
        testPoint(
            "$lang",
            (string) $lang->format('世界'), // 应入一个参数格式化文本
            (string) $lang->format(lang(['世界', 'world'])), // 应入一个Language对象格式化文本
            (string) lang(10000)->lang('en'), // 将Language实例绑定为英文语种
        );
    }

    // 使用实现静态属性类接口的方式自动实例化一个静态属性，通过属性默认值传参
    private static Language|array $langWelcome = ['欢迎使用Dce', 'Welcome to Dce'];

    // 使用静态属性类注解的形式自动实例化一个静态属性，通过注解参数传参
    #[StaticInstance('欢迎欢迎')]
    private static Language $langWelcome2;

    #[Node]
    public function decorate() {
        testPoint( // 打印自动实例化的静态属性
            (string) self::$langWelcome,
            strval(self::$langWelcome2),
        );
    }

    #[Node]
    public function except() {
        // 多语种异常消息使用示例
        $this->exception(new LangException(LangException::EXCEPTION_1));
    }

    #[Node]
    public function model() {
        try {
            // 模型校验器多语种异常消息使用示例
            $model = new TestModel();
            $model->id = 9; // id属性必须为 10-100，当前值异常会在valid的时候抛出异常
            $model->id2 = 101; // id必须小于100，同样异常，你可以注释掉上行而抛出本异常
            $model->valid();
        } catch (Throwable $throwable) {
            $this->exception($throwable);
        }
    }
}