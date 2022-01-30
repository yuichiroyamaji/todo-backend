<?php

namespace Tests\Feature\Api\Todo;

use Tests\CustomTestCase;
use App\Services\UserService;
use App\Services\TaskService;

class TodoDestroyTest extends CustomTestCase
{
    /**
     * App\Http\Controllers\Api\TodoController: destroy()メソッドのテスト
     *
     * @return void
     */
    public function testDestroy()
    {
        $userService = new UserService;
        $taskService = new TaskService;
        // フロントエンドとの接続のためMiddleWare[Cors]でheaderを付与する処理が干渉するため、引数に「test」が存在する場合は[Cors]で処理を外している
        $header = ['Authorization' => 'Basic '.'cmFjaGVsLmdyZWVuQGdtYWlsLmNvbTplMktaNzV4VGNZWkt5NW84'];
        $baseParam = ['test' => true];
        $taskTitle = $this->generateRandomString();
        $taskContent = $this->generateRandomString();

        // CASE_01) 異常系：BASIC認証なし
        $validUserId = $userService->getValidUserId();
        $validTaskId = $taskService->getValidTaskId($validUserId);
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(401);

        // CASE_02) 正常系：有効なユーザーID(必須) + タスクID(必須)
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_03) 異常系：無効なユーザーID
        $invalidUserId = $userService->getInvalidUserId();
        $param = $baseParam + ['user_id' => $invalidUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$invalidUserId."] does not exist"
                 ]);

        // CASE_04) 異常系：未登録のユーザーID
        $unregisteredUserId = 99999;
        $param = $baseParam + ['user_id' => $unregisteredUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$unregisteredUserId."] does not exist"
                 ]);

        // CASE_05) 異常系：数値以外のユーザーID
        $stringUserId = 'aaa';
        $param = $baseParam + ['user_id' => $stringUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id must be an integer."
                 ]);

        // CASE_06) 異常系：無効なタスクID
        $invalidTaskId = $taskService->getInvalidTaskId($validUserId);
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $invalidTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id [".$invalidTaskId."] does not exist"
                 ]);

        // CASE_07) 異常系：未登録のタスクID
        $unregisteredTaskId = 99999;
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $unregisteredTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id [".$unregisteredTaskId."] does not exist"
                 ]);

        // CASE_08) 異常系：数値以外のタスクID
        $stringTaskId = 'aaa';
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $stringTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id must be an integer."
                 ]);

        // CASE_09) 異常系：パラメーターなし
        $param = $baseParam;
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_10) 異常系：user_idのパラメーターなし
        $param = $baseParam + ['task_status' => 1];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_11) 異常系：task_idのパラメーターなし
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('DELETE', route('todo.destroy',''), $param);
        $response->assertStatus(405);
    }
}
