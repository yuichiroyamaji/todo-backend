<?php

namespace App\UseCases\Api\Todo;

use App\Services\UserService;
use App\Services\TaskService;
use App\Utilities\ApiResponseUtility;
use App\Utilities\LogMessageUtility;

/**
 * 指定したユーザーのタスク一覧を取得しAPIレスポンスを返却するUseCaseクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package UseCase
 */
class TodoIndexAction
{
    public function __construct(
        UserService $userService, 
        TaskService $taskService, 
        ApiResponseUtility $apiResponseUtility,
        LogMessageUtility $logMessageUtility
    )
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->apiResponseUtility = $apiResponseUtility;
        $this->logMessageUtility = $logMessageUtility;
    }

    /**
     * invoke
     *
     * @param object $request
     * @return json $response
     */
    public function invoke($request)
    {
        $userId = $request->user_id;
        $err = false;
        try{
            // ユーザーの存在チェック
            if (!$this->userService->isUser($userId)) {
                $err = ['The user id ['.$userId.'] does not exist', 422];
            }else{
                $tasks = $this->taskService->getTasksById($userId)->toArray();
            }
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($tasks);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得成功', 200, $data);
            return response()->json($data);
        }
    }
}
