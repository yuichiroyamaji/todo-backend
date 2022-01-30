<?php

namespace Tests;

use Tests\TestCase;

class CustomTestCase extends TestCase
{
    /**
     * 引数で指定した文字数でランダムな文字列を生成し返却
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomString($length = 8)
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', $length)), 0, $length);
    }
}
