<?php
namespace service;

class LangService {
    public static function loadTextMapping(int|string $textId): array {
        // 你应该根据textId查对应的语种文本映射, 为了方便测试, 这里返回固定的
        return [
            'ru' => '乌拉ypa!',
        ];
    }
}