<?php

namespace App\Enums;

/**
 * ユーザー種別の列挙体
 *
 * @access public
 * @author KawadaRyota <k.ryota0513@gmail.com>
 * @package Enum
 */
class UserRole
{

    /**
     * オーナー（スーパーユーザー）
     */
    public const OWNER = 0;

    /**
     * 管理者
     */
    public const ADMIN = 1;

    /**
     * 講師
     */
    public const MANAGER = 2;

    /**
     * 生徒
     */
    public const MEMBER = 3;

    /**
     * テキストを取得します。
     *
     * @param int $role
     * @return string
     */
    public static function getText(int $role)
    {
        if ($role === self::OWNER) {
            return 'オーナー';
        } elseif ($role === self::ADMIN) {
            return '管理者';
        } elseif ($role === self::MANAGER) {
            return 'マネージャー';
        } elseif ($role === self::MEMBER) {
            return 'メンバー';
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
            self::OWNER => 'オーナー',
            self::ADMIN => '管理者',
            self::MANAGER => 'マネージャー',
            self::MEMBER => 'メンバー',
        ];
    }

    /**
     * タスク管理対象のロールを取得します。
     *
     * @return array
     */
    public static function getTaskManagedRoles()
    {
        return [
            self::MANAGER,
            self::MEMBER
        ];
    }
}
