<?php

namespace Tests\Feature\Api\Todo;

use Tests\CustomTestCase;
use App\Services\UserService;
use App\Services\TaskService;

class TodoUpdateTest extends CustomTestCase
{
    /**
     * App\Http\Controllers\Api\TodoController: update()メソッドのテスト
     *
     * @return void
     */
    public function testUpdate()
    {
        $userService = new UserService;
        $taskService = new TaskService;
        // フロントエンドとの接続のためMiddleWare[Cors]でheaderを付与する処理が干渉するため、引数に「test」が存在する場合は[Cors]で処理を外している
        $header = ['Authorization' => 'Basic '.'cmFjaGVsLmdyZWVuQGdtYWlsLmNvbTplMktaNzV4VGNZWkt5NW84'];
        $baseParam = ['test' => true];
        $taskTitle = $this->generateRandomString();
        $taskContent = $this->generateRandomString();

        /**
         * 共通テストケース
         */

        // CASE_01) 異常系：BASIC認証なし
        $validUserId = $userService->getValidUserId();
        $validTaskId = $taskService->getValidTaskId($validUserId);
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(401);

        // CASE_02) 異常系：無効なユーザーID
        $invalidUserId = $userService->getInvalidUserId();
        $param = $baseParam + ['user_id' => $invalidUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$invalidUserId."] does not exist"
                 ]);

        // CASE_03) 異常系：未登録のユーザーID
        $unregisteredUserId = 99999;
        $param = $baseParam + ['user_id' => $unregisteredUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$unregisteredUserId."] does not exist"
                 ]);

        // CASE_04) 異常系：数値以外のユーザーID
        $stringUserId = 'aaa';
        $param = $baseParam + ['user_id' => $stringUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id must be an integer."
                 ]);

        // CASE_05) 異常系：無効なタスクID
        $invalidTaskId = $taskService->getInvalidTaskId($validUserId);
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $invalidTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id [".$invalidTaskId."] does not exist"
                 ]);

        // CASE_06) 異常系：未登録のタスクID
        $unregisteredTaskId = 99999;
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $unregisteredTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id [".$unregisteredTaskId."] does not exist"
                 ]);

        // CASE_07) 異常系：数値以外のタスクID
        $stringTaskId = 'aaa';
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $stringTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task id must be an integer."
                 ]);

        // CASE_08) 異常系：パラメーターなし
        $param = $baseParam;
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_09) 異常系：user_idのパラメーターなし
        $param = $baseParam + ['task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_10) 異常系：task_idのパラメーターなし
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update',''), $param);
        $response->assertStatus(405);

        /**
         * タスクステータスの更新固有のテストケース
         */

        // CASE_11) 正常系：有効なユーザーID(必須) + タスクID(必須) + タスクステータス(必須)
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 1];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_12) 異常系：数値以外のタスクステータス
        $param = $baseParam + ['user_id' => $validUserId, 'task_status' => 'aaa'];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                  "status" => "failed",
                  "data" => [],
                  "errors" => "The task status must be an integer."
                 ]);

        // CASE_13) 異常系：タスクステータスなし
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                  "status" => "failed",
                  "data" => [],
                  "errors" => "The task status field is required.",
                  'errors' => 'The task status field is required when task title is not present.',
                 ]);

        /**
         * タスクタイトル/タスク内容の更新固有のテストケース
         */

        // CASE_14) 正常系：有効なユーザーID(必須) + タスクID(必須) + タスクタイトル(必須) + タスク内容
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitle, 'task_content' => $taskContent];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_15) 正常系：有効なユーザーID(必須) + タスクID(必須) + タスクタイトル(必須)
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_16) 異常系：task_titleのパラメーターなし
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task title field is required.",
                    'errors' => 'The task status field is required when task title is not present.',
                 ]);
         
        // CASE_17) 異常系：タスクタイトルが255文字以上
        $taskTitleOver255 = $this->generateRandomString(256);
        $param07 = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitleOver255];
        $response = $this->withHeaders($header)->json('PUT', route('todo.update', $validTaskId), $param07);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task title may not be greater than 255 characters."
                 ]);
    }
}
