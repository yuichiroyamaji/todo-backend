<?php

namespace App\Enums;

/**
 * ユーザー種別の列挙体
 *
 * @access public
 * @author KawadaRyota <k.ryota0513@gmail.com>
 * @package Enum
 */
class TaskPriority
{

    /**
     * 緊急
     */
    public const URGENT = 0;

    /**
     * 高
     */
    public const HIGH = 1;

    /**
     * 中
     */
    public const MIDDLE = 2;

    /**
     * 低
     */
    public const LOW = 3;

    /**
     * テキストを取得します。
     *
     * @param int $priority
     * @return string
     */
    public static function getText(int $priority)
    {
        if ($priority === self::URGENT) {
            return '緊急';
        } elseif ($priority === self::HIGH) {
            return '高';
        } elseif ($priority === self::MIDDLE) {
            return '中';
        } elseif ($priority === self::LOW) {
            return '低';
        } else {
            return '';
        }
    }

    /**
     * ステータスリストを取得します。
     *
     * @return array
     */
    public static function getList()
    {
        return [
            self::URGENT => '緊急',
            self::HIGH => '高',
            self::MIDDLE => '中',
            self::LOW => '低',
        ];
    }
}
