<?php

namespace Tests\Feature\Api\Todo;

use Tests\CustomTestCase;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoStoreTest extends CustomTestCase
{
   /**
     * App\Http\Controllers\Api\TodoController: store()メソッドのテスト
     *
     * @return void
     */
    public function testStore()
    {
        $userService = new UserService;
        // フロントエンドとの接続のためMiddleWare[Cors]でheaderを付与する処理が干渉するため、引数に「test」が存在する場合は[Cors]で処理を外している
        $header = ['Authorization' => 'Basic '.'cmFjaGVsLmdyZWVuQGdtYWlsLmNvbTplMktaNzV4VGNZWkt5NW84'];
        $baseParam = ['test' => true];
        $taskTitle = $this->generateRandomString();
        $taskContent = $this->generateRandomString();

        // CASE_01) 異常系：BASIC認証なし
        $validUserId = $userService->getValidUserId();
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitle];
        $response = $this->json('POST', route('todo.store'), $param);
        $response->assertStatus(401);

        // CASE_02) 正常系：有効なユーザーID(必須) + タスクタイトル(必須) + タスク内容
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitle, 'task_content' => $taskContent];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_03) 正常系：有効なユーザーID(必須) + タスクタイトル(必須)
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_04) 異常系：無効なユーザーID
        $invalidUserId = $userService->getInvalidUserId();
        $param = $baseParam + ['user_id' => $invalidUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$invalidUserId."] does not exist"
                 ]);

        // CASE_05) 異常系：未登録のユーザーID
        $unregisteredUserId = 99999;
        $param = $baseParam + ['user_id' => $unregisteredUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$unregisteredUserId."] does not exist"
                 ]);

        // CASE_06) 異常系：数値以外のユーザーID
        $stringUserId = 'aaa';
        $param = $baseParam + ['user_id' => $stringUserId, 'task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id must be an integer."
                 ]);

        // CASE_07) 異常系：タスクタイトルが255文字以上
        $taskTitleOver255 = $this->generateRandomString(256);
        $param = $baseParam + ['user_id' => $validUserId, 'task_title' => $taskTitleOver255];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task title may not be greater than 255 characters."
                 ]);

        // CASE_08) 異常系：パラメーターなし
        $param = $baseParam;
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_09) 異常系：user_idのパラメーターなし
        $param = $baseParam + ['task_title' => $taskTitle];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_10) 異常系：task_titleのパラメーターなし
        $param = $baseParam + ['user_id' => $unregisteredUserId];
        $response = $this->withHeaders($header)->json('POST', route('todo.store'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The task title field is required."
                 ]);
    }
}
