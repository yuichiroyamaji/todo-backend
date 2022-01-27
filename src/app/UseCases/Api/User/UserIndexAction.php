<?php

namespace App\UseCases\Api\User;

use App\Services\UserService;
use App\Utilities\ApiResponseUtility;
use App\Utilities\LogMessageUtility;

/**
 * アクティブなユーザーの一覧を取得しAPIレスポンスを返却するUseCaseクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package UseCase
 */
class UserIndexAction
{
    public function __construct(
        UserService $userService, 
        ApiResponseUtility $apiResponseUtility,
        LogMessageUtility $logMessageUtility
    )
    {
        $this->userService = $userService;
        $this->apiResponseUtility = $apiResponseUtility;
        $this->logMessageUtility = $logMessageUtility;
    }

    /**
     * invoke
     *
     * @param object $request
     * @return json $response
     */
    public function invoke()
    {
        $err = false;
        try{
            $users = $this->userService->getActiveUsers()->toArray();
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('ユーザーリスト取得失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($users);
            $this->logMessageUtility->logApiMessage('ユーザーリスト取得成功', 200, $data);
            return response()->json($data);
        }
    }
}
