<?php

namespace App\UseCases\Api\Todo;

use App\Services\UserService;
use App\Services\TaskService;
use App\Utilities\ApiResponseUtility;
use App\Utilities\LogMessageUtility;
use Illuminate\Support\Facades\DB;

/**
 * 指定されたタスクを削除しAPIレスポンスを返却するUseCaseクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package UseCase
 */
class TodoDestroyAction
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
     * @param integer $taskId
     * @return json $response
     */
    public function invoke($request, $taskId)
    {
        $userId = $request->user_id;
        $err = false;
        try{
            // ユーザーの存在チェック
            if (!$this->userService->isUser($userId)) {
                $err = ['The user id ['.$userId.'] does not exist', 422];
            // タスクの存在チェック
            }elseif (!$this->taskService->isTask($taskId, $userId)) {
                $err = ['The task id ['.$taskId.'] does not exist', 422];
            }else{
                $tasks = DB::transaction(function () use ($taskId, $userId) {
                    $this->taskService->deleteTask($taskId);
                    return $this->taskService->getTasksById($userId)->toArray();
                });
            }
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスク削除失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($tasks);
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスク削除成功', 200, ['task_id' => $taskId]);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得成功：', 200, $data);
            return response()->json($data);
        }
    }

}
