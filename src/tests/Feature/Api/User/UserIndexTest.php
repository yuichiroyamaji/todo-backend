<?php

namespace Tests\Feature\Feature\Api\User;

use Tests\TestCase;

class UserIndexTest extends TestCase
{
    /**
     * App\Http\Controllers\Api\UserController: index()メソッドのテスト
     *
     * @return void
     */
    public function testIndex()
    {
        // フロントエンドとの接続のためMiddleWare[Cors]でheaderを付与する処理が干渉するため、引数に「test」が存在する場合は[Cors]で処理を外している
        $header = ['Authorization' => 'Basic '.'cmFjaGVsLmdyZWVuQGdtYWlsLmNvbTplMktaNzV4VGNZWkt5NW84'];
        $baseParam = ['test' => true];

        // CASE_01) 異常系：BASIC認証なし
        $param = $baseParam;
        $response = $this->json('GET', route('user.index'), $param);
        $response->assertStatus(401);

        // CASE_02) 正常系
        $param = $baseParam;
        $response = $this->withHeaders($header)->json('GET', route('user.index'), $param);
        $response->assertStatus(200)
                 ->assertJson([
                    "status" => "successed",
                    "data" => [],
                    "errors" => ""
                 ]);
    }
}
