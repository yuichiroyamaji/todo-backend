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
                    ->whereIn('role', UserRole::getTaskManagedRoles())
                    ->get();
    }

    /**
     * ユーザーの存在チェック
     * @param int $userId
     * @return boolean
     */
    public function isUser($userId)
    {
        $user = User::where('id', $userId)
                    ->where('deleted_at', null)
                    ->whereIn('role', UserRole::getTaskManagedRoles())
                    ->first();
        return $user ? true : false;
    }

    /**
     * タスク管理対象ユーザーの最初のユーザーのIDを取得
     * @return int $validUserId
     */
    public function getValidUserId()
    {
        $result = User::select('id')
                    ->where('deleted_at', null)
                    ->whereIn('role', UserRole::getTaskManagedRoles())
                    ->first();
        $toArray = $result->toArray();
        $validUserId = $toArray['id'];
        return $validUserId;
    }

    /**
     * タスク管理対象外ユーザーの最初のユーザーのIDを取得
     * @return int $invalidUserId
     */
    public function getInvalidUserId()
    {
        $result = User::select('id')
                    ->where('deleted_at', null)
                    ->whereNotIn('role', UserRole::getTaskManagedRoles())
                    ->first();
        $toArray = $result->toArray();
        $invalidUserId = $toArray['id'];
        return $invalidUserId;
    }
}
