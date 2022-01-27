<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UseCases\Api\User\UserIndexAction;

class UserController extends Controller
{
    /**
     * 表示対象のユーザーのリストを取得
     *
     * @param UserIndexAction $action
     * @return \Illuminate\Http\Response $users
     */
    public function index(UserIndexAction $action)
    {
        $users = $action->invoke();
        return $users;
    }
}
