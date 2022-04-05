## API仕様書

<br>

### API一覧
---

|NO|API名|概要|
|--|--|--|
|1|ユーザー情報取得|ユーザーの一覧を取得します。|
|2|タスク情報取得|指定したユーザーのタスクの一覧を取得します。|
|3|タスク追加|指定したユーザーのタスクを追加します。|
|4|タスク更新|指定したタスクのステータスを更新します。|
|||指定したタスクのタイトル、内容を更新します。|
|5|タスク削除|指定したタスクを削除します。|

<br>
<br>

### 共通仕様
---

▼基本情報
|項目|値|説明|
|--|--|--|
|プロトコル|HTTPS|-|
|Authorization|Basic Auth|(TestAccount) username: system.admin@gmail.com, password: ze2KZ75xTcYZKy5o8|
|MINETYPE|application/json|JSON形式のデータを返却します。|
|文字セット|UTF-8|-|

<br>

▼レスポンス形式
|フィールド|名称|説明|
|--|--|--|
|status|リクエストステータス|"successed" または "failed" が連携されます。|
|data|レスポンスデータ|レスポンスデータが連携されます。エラーの場合は空の配列が設定されます。|
|errors|エラー内容|エラーの内容が連携されます。成功した場合は空の値が設定されます。|

<br>
<br>

### 1. ユーザー情報取得
---
以下4つの役職のうち、タスク管理対象としている「管理職」「一般職」のユーザーの一覧を取得し返却します。
- ビジネスオーナー
- システム管理者
- 管理職
- 一般職

<br>
▼基本情報
<br>

|項目|値|
|--|--|
|リクエストURL|https://hereis.myyuichiroyamajitododemo.com/api/users|
|メソッド|GET|


<br>
▼リクエストパラメータ
<br>
なし

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|ユーザーID|ユーザーに紐づく一意のIDです。|
|name|名前|ユーザーのfirst nameです。|
|img_path|画像パス|ユーザに紐づく画像のパスです。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 3,
            "name": "Rachel",
            "img_path": "https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/images/rachel.png"
        },
        {
            "id": 4,
            "name": "Joey",
            "img_path": "https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/images/joey.png"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|

<br>
<br>

### 2. タスク情報取得
---
パラメーターで指定したユーザーIDに紐づくユーザーのタスクを全権取得し返却します。

<br>
▼基本情報
<br>

|項目|値|
|--|--|
|リクエストURL|https://hereis.myyuichiroyamajitododemo.com/api/todo?user_id={USER_ID}|
|メソッド|GET|


<br>
▼リクエストパラメータ
<br>

|パラメータ名|名称|説明|値|必須|
|--|--|--|--|--|
|user_id|ユーザーID|タスクを取得したいユーザーのID|数値で指定|○|

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|タスクID|タスクに紐づく一意のIDです。|
|task_status|タスクステータス|タスクの完了（1）、未完了（0）の状態です。|
|task_title|タスクタイトル|タスクのタイトルです。|
|task_content|タスク内容|タスクの内容です。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 2,
            "task_status": 1,
            "task_title": "タスク4-2",
            "task_content": "タスク4-2タスク4-2タスク4-2タスク4-2タスク4-2"
        },
        {
            "id": 3,
            "task_status": 0,
            "task_title": "タスク4-3",
            "task_content": "タスク4-3タスク4-3タスク4-3タスク4-3タスク4-3"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|
|422|The user id [{USER_ID}] does not exist|無効なユーザーIDです。|
|422|The user id must be an integer.|ユーザーIDが数値以外です。|
|422|The user id field is required.|ユーザーIDが連携されていません。|

(失敗時：サンプルレスポンス)
```
{
    "status": "failed",
    "data": [],
    "errors": "The user id must be an integer."
}
```

<br>
<br>

### 3. タスク追加
---
パラメーターで指定したユーザーIDのタスクを追加し、追加後のタスク一覧を返却します。

<br>
▼基本情報
<br>

|項目|値|
|--|--|
|リクエストURL|https://hereis.myyuichiroyamajitododemo.com/api/todo|
|メソッド|POST|


<br>
▼リクエストパラメータ
<br>

|パラメータ名|名称|説明|値|必須|
|--|--|--|--|--|
|user_id|ユーザーID|タスクを取得したいユーザーのID|数値で指定|○|
|task_title|タスクタイトル|タスクのタイトル|255字までの文字列にて指定|○|
|task_content|タスク内容|タスクの内容|文字列にて指定||

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|タスクID|タスクに紐づく一意のIDです。|
|task_status|タスクステータス|タスクの完了（1）、未完了（0）の状態です。|
|task_title|タスクタイトル|タスクのタイトルです。|
|task_content|タスク内容|タスクの内容です。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 8,
            "task_status": 0,
            "task_title": "タスク7-1",
            "task_content": "タスク7-1タスク7-1タスク7-1タスク7-1タスク7-1"
        },
        {
            "id": 18,
            "task_status": 1,
            "task_title": "stored",
            "task_content": "stored"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|
|422|The user id [{USER_ID}] does not exist|無効なユーザーIDです。|
|422|The user id must be an integer.|ユーザーIDが数値以外です。|
|422|The user id field is required.|ユーザーIDが連携されていません。|
|422|The task title may not be greater than 255 characters.|タスクのタイトルが255字以上です。|
|422|The task title field is required.|タスクタイトルが連携されていません。|

(失敗時：サンプルレスポンス)
```
{
    "status": "failed",
    "data": [],
    "errors": "The task title may not be greater than 255 characters."
}
```

<br>
<br>

### 4. タスク更新
---
URL内で指定したタスクIDのタスク情報を更新し、更新後のタスク一覧を返却します。

<br>
▼基本情報
<br>

|項目|値|
|--|--|
|リクエストURL|https://hereis.myyuichiroyamajitododemo.com/api/todo/{TASK_ID}|
|メソッド|PUT|

<br>

#### 4-1. タスクステータスの更新

<br>
▼リクエストパラメータ
<br>

|パラメータ名|名称|説明|値|必須|
|--|--|--|--|--|
|task_id|タスクID|タスクに紐づく一意のID|数値にてURL内に設定|○|
|user_id|ユーザーID|タスクを取得したいユーザーのID|数値で指定|○|
|task_status|タスクステータス|タスクの完了/未完了の状態|数値(0/1)にて指定|○|

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|タスクID|タスクに紐づく一意のIDです。|
|task_status|タスクステータス|タスクの完了（1）、未完了（0）の状態です。|
|task_title|タスクタイトル|タスクのタイトルです。|
|task_content|タスク内容|タスクの内容です。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 8,
            "task_status": 0,
            "task_title": "タスク7-1",
            "task_content": "タスク7-1タスク7-1タスク7-1タスク7-1タスク7-1"
        },
        {
            "id": 18,
            "task_status": 0,
            "task_title": "stored",
            "task_content": "stored"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|
|422|The user id [{USER_ID}] does not exist|無効なユーザーIDです。|
|422|The user id must be an integer.|ユーザーIDが数値以外です。|
|422|The user id field is required.|ユーザーIDが連携されていません。|
|422|The task status id be an integer.|タスクIDが数値以外です。|
|422|The task status id field is required.|タスクIDが連携されていません。|
|422|The task status must be an integer.|タスクステータスが数値以外です。|
|422|The task status field is required when task title is not present.|タスクステータスが連携されていません。|

(失敗時：サンプルレスポンス)
```
{
    "status": "failed",
    "data": [],
    "errors": "The task status field is required when task title is not present."
}
```

<br>

#### 4-2. タスクタイトル･内容の更新

<br>
▼リクエストパラメータ
<br>

|パラメータ名|名称|説明|値|必須|
|--|--|--|--|--|
|task_id|タスクID|タスクに紐づく一意のID|数値にてURL内に設定|○|
|user_id|ユーザーID|タスクを取得したいユーザーのID|数値で指定|○|
|task_title|タスクタイトル|タスクのタイトル|255字までの文字列にて指定|○|
|task_content|タスク内容|タスクの内容|文字列にて指定||

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|タスクID|タスクに紐づく一意のIDです。|
|task_status|タスクステータス|タスクの完了（1）、未完了（0）の状態です。|
|task_title|タスクタイトル|タスクのタイトルです。|
|task_content|タスク内容|タスクの内容です。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 8,
            "task_status": 0,
            "task_title": "タスク7-1",
            "task_content": "タスク7-1タスク7-1タスク7-1タスク7-1タスク7-1"
        },
        {
            "id": 18,
            "task_status": 0,
            "task_title": "updated",
            "task_content": "updated"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|
|422|The user id [{USER_ID}] does not exist|無効なユーザーIDです。|
|422|The user id must be an integer.|ユーザーIDが数値以外です。|
|422|The user id field is required.|ユーザーIDが連携されていません。|
|422|The task status id be an integer.|タスクIDが数値以外です。|
|422|The task status id field is required.|タスクIDが連携されていません。|
|422|The task title may not be greater than 255 characters.|タスクのタイトルが255字以上です。|
|422|The task title field is required.|タスクタイトルが連携されていません。|

(失敗時：サンプルレスポンス)
```
{
    "status": "failed",
    "data": [],
    "errors": "The user id [100] does not exist"
}
```

<br>
<br>

### 5. タスク削除
---
URL内で指定したタスクIDのタスク情報を削除し、削除後のタスク一覧を返却します。

<br>
▼基本情報
<br>

|項目|値|
|--|--|
|リクエストURL|https://hereis.myyuichiroyamajitododemo.com/api/todo/{TASK_ID}|
|メソッド|DELETE|

<br>
▼リクエストパラメータ
<br>

|パラメータ名|名称|説明|値|必須|
|--|--|--|--|--|
|task_id|タスクID|タスクに紐づく一意のID|数値にてURL内に設定|○|
|user_id|ユーザーID|タスクを取得したいユーザーのID|数値で指定|○|

<br>
▼レスポンス（成功時：data）

|フィールド|名称|説明|
|--|--|--|
|id|タスクID|タスクに紐づく一意のIDです。|
|task_status|タスクステータス|タスクの完了（1）、未完了（0）の状態です。|
|task_title|タスクタイトル|タスクのタイトルです。|
|task_content|タスク内容|タスクの内容です。|

(成功時：サンプルレスポンス)
```
{
    "status": "successed",
    "data": [
        {
            "id": 8,
            "task_status": 0,
            "task_title": "タスク7-1",
            "task_content": "タスク7-1タスク7-1タスク7-1タスク7-1タスク7-1"
        }
    ],
    "errors": ""
}
```
<br>

▼レスポンス（失敗時：statusCd / errors）

|HTTPステータスコード|エラーメッセージ|説明|
|--|--|--|
|401|Unauthorized|認証情報に誤りがあります。|
|422|The user id [{USER_ID}] does not exist|無効なユーザーIDです。|
|422|The user id must be an integer.|ユーザーIDが数値以外です。|
|422|The user id field is required.|ユーザーIDが連携されていません。|
|422|The task status id be an integer.|タスクIDが数値以外です。|
|422|The task status id field is required.|タスクIDが連携されていません。|

(失敗時：サンプルレスポンス)
```
{
    "status": "failed",
    "data": [],
    "errors": "The task id field is required."
}
```