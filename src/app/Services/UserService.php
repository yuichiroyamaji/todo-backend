<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;

/**
 * ユーザー情報を管理するServiceクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package Service
 */
class UserService
{
    /**
     * インスタンスを作成します。
     *
     * @param 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * すべてのユーザー情報を取得します。
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::get();
    }

    /**
     * タスク管理対象ユーザーを取得
     * @return Users[]
     */
    public function getActiveUsers()
    {
        return User::select('id', 'name', 'img_path')
                    ->where('deleted_at', null)
                    ->whereIn('role', [UserRole::MANAGER, UserRole::MEMBER])
                    ->get();
    }

    /**
     * ユーザーの存在チェック
     * @param string $userId
     * @return boolean
     */
    public function isUser($userId)
    {
        $user = User::where('id', $userId)
                    ->where('deleted_at', null)
                    ->whereIn('role', [UserRole::MANAGER, UserRole::MEMBER])
                    ->first();
        return $user ? true : false;
    }
}
