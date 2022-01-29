## <u>TodoDemoアプリ(バックエンド)</u>

### 概要図

<img src="https://databucketyamaji.s3.us-east-2.amazonaws.com/images/backend.png">

### 基本構成

|NO|項目|環境|ソフトウェア|
|--|--|--|--|
|1|OS|AWS ECS Fargate|Debian GNU/Linux 10.4|
|2|WEBサーバー|AWS ECS Fargate|Apache 2.4.38|
|3|プログラミング言語|AWS ECS Fargate|PHP 7.3.18|
|4|フレームワーク|AWS ECS Fargate|Laravel 6.2|
|5|データベース|AWS RDS|MySQL 8.0|

### 利用AWSサービス

- ECS
- ECR
- ELB (ALB)
- CodePipeline (CodeCommit, CodeBuild, CodeDeploy)
- RDS
- S3
- CloudWatch
- Route 53

### リポジトリ

|項目|値|
|--|--|
|バージョン管理システム|Git|
|ソースコード管理ツール|GitHub|
|リポジトリURL|https://github.com/yuichiroyamaji/todo-backend.git|
|ブランチ|main, develop|

### ディレクトリ構成

```
[プロジェクトルート]
  ├ doc/
  │  └ cfn-template/          ・・・ CloudFormation template
  │  └ dockerfile/            ・・・ 環境別Dockerfile
  ├ docker/                   ・・・ コンテナマウント用Webサーバー設定ファイル
  ├ src/                      ・・・ Laravelプロジェクトファイル
  ├ appspec.yml
  ├ buildspec.yml
  ├ Dockerfile
  ├ taskdef.json
  └ README.md
```

### ビルド/デプロイ

|利用サービス|内訳|動作|
|--|--|--|
|AWS CodePipeline|CodeCommit|リポジトリ<b>【deveop】</b>ブランチへのプッシュで起動|
||CodeBuild|Docker imageをビルドし、ECRへプッシュ|
||CodeDeploy|タスクを更新、ECSへ反映|

### 実装API

##### ▼API一覧

{DOMAIN} = https://hereare.myyuichiroyamajitododemo.com/

|NO|名目|メソッド|URL|
|--|--|--|--|
|1|ユーザー情報の取得|GET|{DOMAIN}/api/users|
|2|TODOタスク情報の取得|GET|{DOMAIN}/api/todo?user_id={USER_ID}|
|3|TODOタスクの作成|POST|{DOMAIN}/api/todo|
|4|TODOタスクの更新|PUT|{DOMAIN}/api/todo/{TASK_ID}|
|5|TODOタスクの削除|DELETE|{DOMAIN}/api/todo/{TASK_ID}|

##### ▼レスポンス成功例

```
{
    "status": "successed",
    "data": [
        {
            "id": 4,
            "task_status": 0,
            "task_title": "タスク5-1",
            "task_content": "タスク5-1タスク5-1タスク5-1タスク5-1タスク5-1"
        },
        {
            "id": 5,
            "task_status": 1,
            "task_title": "タスク5-2",
            "task_content": "タスク5-2タスク5-2タスク5-2タスク5-2タスク5-2"
        }
    ],
    "errors": ""
}
```

##### ▼レスポンス失敗例

```
{
    "status": "failed",
    "data": [],
    "errors": "The user id [100] does not exist"
}
```

### 設計

##### ▼コンポーネント設計

<img src="https://databucketyamaji.s3.us-east-2.amazonaws.com/images/cleanarchitecture.jpeg" width="60%">

|図内の要素名|Laravelコンポーネント名|責務|
|--|--|--|
|Frameworks & Drivers|Laravel Frameworks|-|
|Interface Adapters|RequestForm|リクエストフォーマットのバリデーション|
||Controllers|リクエストの受理、レスポンスの返却|
|Application Business Rules|UseCases|Service, Utilityの呼出し、処理後の値のControllerへの返却|
|Enterprise Business Rules|Model|データベーステーブルに対応|
||Services|Modelに紐づくビジネスロジック|
||Utilities|Modelに紐づかない共通ビジネスロジック|

##### ▼データフロー

Routing → RequestForm -> Controller → UseCase -> Service/Utility → UseCase → Controller → response()

(例) Todoタスク新規登録

1. Routing
    (route\api.php)
    ```php
    Route::group(['namespace' => 'Api','middleware' => ['auth.basic', 'cors', 'logs']], function() {

        // Userルート
        Route::get('/users', 'UserController@index');

        // Todoタスクルート
        Route::apiResource('/todo', 'ToDoController')
                ->only(['index', 'store', 'update', 'destroy'])
                ->parameters(['todo' => 'task_id']);

    });

    ```
1. RequestForm
   (App\Http\Requests\Api\TodoUpdateRequest.php)
    ```php
    public function rules()
    {
        return [
            'user_id' => ['required',  'integer'],
            'task_title' => ['required', 'max:255'],
        ];
    }
    ```
1. Controller
   (App\Http\Controllers\Api\ToDoController.php)
    ```php
    public function store(TodoStoreRequest $request, TodoStoreAction $action)
    {
        $tasks = $action->invoke($request);
        return $tasks;
    }
    ```
1. UseCase
   (App\UseCases\Api\Todo\TodoStoreAction.php)
    ```php
    public function invoke($request)
    {
        $userId = $request->user_id;
        $taskTitle = $request->task_title;
        $taskContent = $request->task_content;
        $err = false;
        try{
            // ユーザーの存在チェック
            if (!$this->userService->isUser($userId)) {
                $err = ['The user id ['.$userId.'] does not exist', 422];
            }else{
                $tasks = DB::transaction(function () use ($userId, $taskTitle, $taskContent) {
                    $this->todoService->storeTodoTask($userId, $taskTitle, $taskContent);
                    return $this->todoService->getTodoTasksById($userId)->toArray();
                });
            }
        }catch(\Exception $e){
            $err = [$e->getMessage(), 500];
        }
        if($err){
            $data = $this->apiResponseUtility->formFailResponseData($err[0]);
            $statusCd = $err[1];
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク登録失敗', $statusCd, $data);
            return response()->json($data, $statusCd);
        }else{
            $data = $this->apiResponseUtility->formSuccessResponseData($tasks);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク登録成功', 201, ['task_title' => $taskTitle, 'task_content' => $taskContent]);
            $this->logMessageUtility->logApiMessage('ユーザーID['.$userId.']のタスク取得成功：', 200, $data);
            return response()->json($data);
        }
    }
    ```
1. Service
   (App\Services\UserService.php)
    ```php
    public function isUser($userId)
    {
        $user = User::where('id', $userId)
                    ->where('deleted_at', null)
                    ->whereIn('role', [UserRole::MANAGER, UserRole::MEMBER])
                    ->first();
        return $user ? true : false;
    }
    ```

   (App\Services\TodoService.php)
    ```php
    public function storeTodoTask($userId, $taskTitle, $taskContent)
    {
        Task::create([
                'in_charge_user_id' => $userId,
                'task_title' => $taskTitle,
                'task_content' => $taskContent
            ]);
    }
    ```
1. Utility
   (App\Utilities\LogMessageUtility.php)
    ```php
    public function logApiMessage($message, $statusCd, $data)
    {
        $logContents = [
            'message' => $message,
            'statusCode' => $statusCd,
            'data' => $data,
        ];
        $statusCd < 300 ? Log::info($logContents) : Log::error($logContents);
    }
    ```