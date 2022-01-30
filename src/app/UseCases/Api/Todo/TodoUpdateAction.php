<?php

namespace App\UseCases\Api\Todo;

use App\Services\UserService;
use App\Services\TaskService;
use App\Utilities\ApiResponseUtility;
use App\Utilities\LogMessageUtility;
use Illuminate\Support\Facades\DB;

/**
 * 指定タスクを更新しAPIレスポンスを返却するUseCaseクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package UseCase
 */
class TodoUpdateAction
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
        // タスクステータスが含まれている場合はステータスの更新
        if(isset($request->task_status)){
            return $this->updateTaskStatus($request, $taskId);
        // 含まれていない場合はタスクタイトル/タスク内容の更新
        }else{
            return $this->updateTaskTitleContent($request, $taskId);
        }
    }

    /**
     * タスクのステータスの更新
     *
     * @param object $request
     * @param integer $taskId
     * @return json $response
     */
    private function updateTaskStatus($request, $taskId){
        $userId = $request->user_id;
        $taskStatus = $request->task_status;
        $err = false;
        try{
            // ユーザーの存在チェック
            if (!$this->userService->isUser($userId)) {
                $err = ['The user id ['.$userId.'] does not exist', 422];
            // タスクの存在チェック
            }elseif (!$this->taskService->isTask($taskId, $userId)) {
                $err = ['The task id ['.$taskId.'] does not exist', 422];
            }else{
                $tasks = DB::transaction(function () use ($taskId, $taskStatus, $userId) {
                    $this->taskService->updateTaskStatus($taskId, $taskStatus);
                    return $this->taskService->getTasksById($userId)->toArray();
                });
            }
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスクステータス更新失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($tasks);
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスクステータス更新成功', 200, ['task_id' => $taskId, 'task_status' => $taskStatus]);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得成功：', 200, $data);
            return response()->json($data);
        }
    }

    /**
     * タスクのタイトルと内容の更新
     *
     * @param object $request
     * @param integer $taskId
     * @return json $response
     */
    private function updateTaskTitleContent($request, $taskId){
        $userId = $request->user_id;
        $taskTitle = $request->task_title;
        $taskContent = $request->task_content;
        $err = false;
        try{
            // ユーザーの存在チェック
            if (!$this->userService->isUser($userId)) {
                $err = ['The user id ['.$userId.'] does not exist', 422];
            // タスクの存在チェック
            }elseif (!$this->taskService->isTask($taskId, $userId)) {
                $err = ['The task id ['.$taskId.'] does not exist', 422];
            }else{
                $tasks = DB::transaction(function () use ($taskId, $taskTitle, $taskContent, $userId) {
                    $this->taskService->updateTaskTitleContent($taskId, $taskTitle, $taskContent);
                    return $this->taskService->getTasksById($userId)->toArray();
                });
            }
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスク更新失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($tasks);
            $this->logMessageUtility->logApiMessage('タスクID['.$taskId.']のタスク更新成功', 200, ['task_id' => $taskId, 'task_title' => $taskTitle, 'task_content' => $taskContent]);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得成功：', 200, $data);
            return response()->json($data);
        }
    }

}
