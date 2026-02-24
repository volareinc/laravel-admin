# APIテスター

`api-tester` は `laravel` 向けに開発された API テストツールで、`postman` のように Laravel API をテストできます。

![wx20170809-164424](https://user-images.githubusercontent.com/1479100/29112946-1e32971c-7d22-11e7-8cc0-5b7ad25d084e.png)

## インストール

```shell
$ composer require laravel-admin-ext/api-tester -vvv

$ php artisan vendor:publish --tag=api-tester

```
次に、以下のコマンドを実行してメニューと権限をインポートします（手動で追加することもできます）：

```shell
$ php artisan admin:import api-tester
```

管理メニューにエントリーリンクが表示されます。`http://localhost/admin/api-tester` にアクセスしてください。

## 使い方

`routes/api.php` を開き、テスト用の API を追加してみましょう：

```php
Route::get('test', function () {
    return 'hello world';
});
```

`api-tester` ページを開くと、左側に `api/test` が表示されます。選択して `Send` ボタンをクリックすると、API にリクエストが送信されます。

### Login as

`Login as` にログインしたいユーザーの ID を入力すると、そのユーザーとして API をリクエストできます。以下の API を追加します：

```php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});
```
`Login as` 入力欄にユーザー ID を入力してから API をリクエストすると、そのユーザーのモデルがレスポンスとして返されます。

### Parameters

API のリクエストパラメータを設定するために使用します。タイプは文字列またはファイルを指定できます。以下の API を追加します：

```php
use Illuminate\Http\Request;

Route::get('parameters', function (Request $request) {
    return $request->all();
});
```

パラメータを入力してリクエストを送信すると、結果を確認できます。
