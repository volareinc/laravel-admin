# クイックスタート

ここでは `Laravel` に付属する `users` テーブルを例として使用します。テーブル構造は以下の通りです：
```sql
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```
このテーブルに対応するモデルは `App\User.php` です。

以下の手順に従って `users` テーブルの `CRUD` インターフェースを構築できます。

## コントローラーの追加

以下のコマンドで `App\User` モデル用のコントローラーを作成します。

```php
php artisan admin:make UserController --model=App\\User

// Windows 環境の場合は以下を使用:
php artisan admin:make UserController --model=App\User
```
上記のコマンドにより `app/Admin/Controllers/UserController.php` にコントローラーが生成されます。

## ルートの追加

`app/Admin/routes.php` にルートを追加します：
```
$router->resource('demo/users', UserController::class);
```

## 左メニュー項目の追加

`http://localhost:8000/admin/auth/menu` を開き、メニューリンクを追加してページを更新すると、左側のメニューバーにリンク項目が表示されます。

> `uri` にはルートのプレフィックスを含まないパス部分を入力します。例えば、フルパスが `http://localhost:8000/admin/demo/users` の場合、`demo/users` と入力します。外部リンクを追加する場合は、`http://laravel-admin.org/` のように完全な URL を入力してください。

### メニューの翻訳

言語ファイルの `menu_titles` インデックスにメニュータイトルを追加します。
例えば「Work Units」というタイトルの場合：

resources/lang/es/admin.php 内で：
```php
...
// 小文字にし、スペースを _ に置換
'menu_titles' => [
    'work_units' => 'Unidades de trabajo'
],
```

## Grid と Form の構築

残りの作業は `app/Admin/Controllers/UserController.php` を開き、`form()` メソッドと `grid()` メソッドに `model-grid` と `model-form` を使って数行のコードを記述することです。詳細については [model-grid](/ja/model-grid.md) と [model-form](/ja/model-form.md) をお読みください。
