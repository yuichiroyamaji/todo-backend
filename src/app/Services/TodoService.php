<?php

namespace App\Services;

use App\Models\Task;

/**
 * タスク情報を管理するServiceクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package Service
 */
class TodoService
{
    /**
     * user_idに紐付くタスクを取得
     * @param integer $userId
     * @return Tasks[]
     */
    public function getTodoTasksById($userId)
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
    public function storeTodoTask($userId, $taskTitle, $taskContent)
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
    public function isTask($taskId)
    {
        $tasks = Task::where('id', $taskId)->first();
        return $tasks ? true : false;
    }

    /**
     * 指定したタスクの更新
     * @param integer $taskId
     * @param string $taskTitle
     * @param string $taskContent
     * @return void()
     */
    public function updateTodoTaskTitleContent($taskId, $taskTitle, $taskContent)
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
    public function updateTodoTaskStatus($taskId, $taskStatus)
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
    public function deleteTodoTask($taskId)
    {
        Task::where('id', $taskId)->delete();
    }

}
