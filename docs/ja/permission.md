# 権限管理

`laravel-admin` には `RBAC` 権限管理モジュールが組み込まれています。左サイドバーの `Auth` を展開すると、ユーザー、権限、ロールの管理パネルが表示されます。権限管理の使い方は以下の通りです。

## ルート権限

`laravel-admin 1.5` では、権限とルートが紐付けられています。権限の編集ページで、現在の権限がアクセスできるルートを設定します。`HTTP method` セレクトボックスでパスへのアクセスメソッドを選択し、`HTTP path` テキストエリアにアクセスするパスを入力します。

例えば、権限を追加して `/admin/users` パスに GET メソッドでアクセスできるようにする場合、`HTTP method` で `GET` を選択し、`HTTP path` に `/users` を入力します。

`/admin/users` プレフィックスを持つすべてのパスにアクセスできるようにしたい場合は、`HTTP path` に `/users*` を入力します。権限に複数のアクセスパスが含まれる場合は、各パスを改行で区切ってください。

## ページ権限

ページ内でユーザーの権限を制御したい場合は、以下の例を参考にしてください。

### 例1

例えば、記事モジュールがあり、記事作成を例として説明します。

まず `http://localhost/admin/auth/permissions` を開き、slug フィールドに `create-post`、name フィールドに `Create post` を入力し、この権限を適切なロールに割り当てます。

コントローラーのアクション内で以下のように記述します：
```php
use Encore\Admin\Auth\Permission;

class PostController extends Controller
{
    public function create()
    {
        // 権限チェック。`create-post` 権限を持つロールのみがこのアクションにアクセスできます
        Permission::check('create-post');
    }
}
```

### 例2

ユーザーに表示されるページ要素を制御したい場合は、まず権限を定義する必要があります。例えば `delete-image` と `view-title-column` はそれぞれ画像削除の権限とグリッド内のカラム表示の権限を制御します。これらの2つの権限をロールに割り当て、以下のコードをグリッドに追加します：
```php
$grid->actions(function ($actions) {

    // この権限を持たないロールは、アクションカラムの削除ボタンが表示されません
    if (!Admin::user()->can('delete-image')) {
        $actions->disableDelete();
    }
});

// `view-title-column` 権限を持つロールのみがグリッドでこのカラムを表示できます
if (Admin::user()->can('view-title-column')) {
    $grid->column('title');
}
```

## その他のメソッド

現在のユーザーオブジェクトを取得します。
```php
Admin::user();
```

現在のユーザー ID を取得します。
```php
Admin::user()->id;
```

ユーザーのロールを取得します。
```php
Admin::user()->roles;
```

ユーザーの権限を取得します。
```php
Admin::user()->permissions;
```

ユーザーが指定のロールかどうかを確認します。
```php
Admin::user()->isRole('developer');
```

ユーザーが権限を持っているか確認します。
```php
Admin::user()->can('create-post');
```

ユーザーが権限を持っていないか確認します。
```php
Admin::user()->cannot('delete-post');
```

ユーザーがスーパー管理者かどうかを確認します。
```php
Admin::user()->isAdministrator();
```

ユーザーがいずれかのロールに属しているか確認します。
```php
Admin::user()->inRoles(['editor', 'developer']);
```

## 権限ミドルウェア

ルートで権限ミドルウェアを使用して、ルーティングの権限を制御できます。

```php

// ロール `administrator` と `editor` にグループ配下のルートへのアクセスを許可します。
Route::group([
    'middleware' => 'admin.permission:allow,administrator,editor',
], function ($router) {

    $router->resource('users', UserController::class);
    ...

});

// ロール `developer` と `operator` にグループ配下のルートへのアクセスを拒否します。
Route::group([
    'middleware' => 'admin.permission:deny,developer,operator',
], function ($router) {

    $router->resource('users', UserController::class);
    ...

});

// 権限 `edit-post`、`create-post`、`delete-post` を持つユーザーがグループ配下のルートにアクセスできます。
Route::group([
    'middleware' => 'admin.permission:check,edit-post,create-post,delete-post',
], function ($router) {

    $router->resource('posts', PostController::class);
    ...

});
```

権限ミドルウェアの使い方は、他のミドルウェアと同じです。
