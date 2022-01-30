<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\UserRole;

/**
 * タスク情報を管理するServiceクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package Service
 */
class TaskService
{
    /**
     * user_idに紐付くタスクを取得
     * @param integer $userId
     * @return Tasks[]
     */
    public function getTasksById($userId)
    {
        return Task::select('id', 'task_status', 'task_title', 'task_content')
                    ->where('in_charge_user_id', $userId)
                    ->get();
    }

    /**
     * user_idに紐付くタスクを新規作成
     * @param integer $userId
     * @param string $taskTitle
     * @param string $taskContent
     * @return void()
     */
    public function storeTask($userId, $taskTitle, $taskContent)
    {
        Task::create([
                'in_charge_user_id' => $userId,
                'task_title' => $taskTitle,
                'task_content' => $taskContent
            ]);
    }

    /**
     * タスクの存在チェック
     * @param string $taskId
     * @return boolean
     */
    public function isTask($taskId, $userId)
    {
        $tasks = Task::where([
                    'id' => $taskId, 
                    'in_charge_user_id' => $userId
                ])->first();
        return $tasks ? true : false;
    }

    /**
     * 指定したタスクの更新
     * @param integer $taskId
     * @param string $taskTitle
     * @param string $taskContent
     * @return void()
     */
    public function updateTaskTitleContent($taskId, $taskTitle, $taskContent)
    {
        Task::where('id', $taskId)
                ->update([
                    'task_title' => $taskTitle,
                    'task_content' => $taskContent
                ]);
    }

    /**
     * 指定したタスクのタスクステータスの更新
     * @param integer $taskId
     * @param string $taskStaus
     * @return void()
     */
    public function updateTaskStatus($taskId, $taskStatus)
    {
        Task::where('id', $taskId)
                ->update([
                    'task_status' => $taskStatus,
                ]);
    }

    /**
     * 指定したタスクの削除
     * @param integer $taskId
     * @return void()
     */
    public function deleteTask($taskId)
    {
        Task::where('id', $taskId)->delete();
    }

    /**
     * タスク管理対応のユーザーの最初のタスクのIDを取得
     * 
     * @return validTaskId
     */
    public function getValidTaskId($userId)
    {
        $result = Task::select('id')
                    ->where('in_charge_user_id', '=', $userId)
                    ->first();
        $toArray = $result->toArray();
        $validTaskId = $toArray['id'];
        return $validTaskId;
    }

    /**
     * タスク管理対応外のユーザーの最初のタスクのIDを取得
     * 
     * @return invalidTaskId
     */
    public function getInvalidTaskId($userId)
    {
        $result = Task::select('id')
                    ->where('in_charge_user_id', '<>', $userId)
                    ->first();
        $toArray = $result->toArray();
        $invalidTaskId = $toArray['id'];
        return $invalidTaskId;
    }

}
