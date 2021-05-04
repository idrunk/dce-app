<?php
namespace service;

use dce\base\Exception;
use dce\i18n\Language;

class LangException extends Exception {
    #[Language(['这是什么异常?', 'What fuck was this ?'])]
    public const EXCEPTION_1 = 10086;
}