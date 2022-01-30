<?php

namespace Tests\Feature\Api\Todo;

use Tests\CustomTestCase;
use App\Services\UserService;

class TodoIndexTest extends CustomTestCase
{
    /**
     * App\Http\Controllers\Api\TodoController: index()メソッドのテスト
     *
     * @return void
     */
    public function testIndex()
    {
        $userService = new UserService;
        // フロントエンドとの接続のためMiddleWare[Cors]でheaderを付与する処理が干渉するため、引数に「test」が存在する場合は[Cors]で処理を外している
        $header = ['Authorization' => 'Basic '.'cmFjaGVsLmdyZWVuQGdtYWlsLmNvbTplMktaNzV4VGNZWkt5NW84'];
        $baseParam = ['test' => true];

        // CASE_01) 異常系：BASIC認証なし
        $validUserId = $userService->getValidUserId();
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->json('GET', route('todo.index'), $param);
        $response->assertStatus(401);

        // CASE_02) 正常系：有効なユーザーID
        $param = $baseParam + ['user_id' => $validUserId];
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);

        // CASE_03) 異常系：無効なユーザーID
        $invalidUserId = $userService->getInvalidUserId();
        $param = $baseParam + ['user_id' => $invalidUserId];
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$invalidUserId."] does not exist"
                 ]);

        // CASE_04) 異常系：未登録のユーザーID
        $unregisteredUserId = 99999;
        $param = $baseParam + ['user_id' => $unregisteredUserId];
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id [".$unregisteredUserId."] does not exist"
                 ]);

        // CASE_05) 異常系：数値以外のユーザーID
        $stringUserId = 'aaa';
        $param = $baseParam + ['user_id' => $stringUserId];
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id must be an integer."
                 ]);

        // CASE_06) 異常系：パラメーター名無効
        $param = $baseParam + ['INVALID_PARAM' => $validUserId];
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);

        // CASE_07) 異常系：パラメーターなし
        $param = $baseParam;
        $response = $this->withHeaders($header)->json('GET', route('todo.index'), $param);
        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "failed",
                    "data" => [],
                    "errors" => "The user id field is required."
                 ]);
    }
}
