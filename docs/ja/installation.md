# インストール

> このパッケージは PHP 7 以上および Laravel 5.5 が必要です。旧バージョンについては [1.4](http://laravel-admin.org/docs/v1.4/#/) を参照してください。

まず、Laravel をインストールし、データベース接続の設定が正しいことを確認してください。

次に、以下のコマンドでこのパッケージをインストールします：
```
composer require volareinc/laravel-admin "1.5.*"
```

以下のコマンドでアセットと設定ファイルを公開します：
```
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
```

上記コマンドの実行後、設定ファイルが `config/admin.php` に生成されます。このファイルでデフォルトのインストールディレクトリ（```/app/Admin```）、データベース接続、テーブル名などを変更できます。

最後に、以下のコマンドを実行してインストールを完了します：
```
php artisan admin:install
```

正常に動作しているか確認するには、`php artisan serve` を実行し、ブラウザで `http://localhost/admin/` を開いてください。ユーザー名 `admin`、パスワード `admin` でログインできます。

## 生成されるファイル

インストールが完了すると、プロジェクトディレクトリに以下のファイルが生成されます。

### 設定ファイル

インストール完了後、すべての設定は `config/admin.php` ファイルに格納されます。

### 管理画面ファイル

インストール後、`app/Admin` ディレクトリが作成されます。開発作業のほとんどはこのディレクトリ配下で行います。

```
app/Admin
├── Controllers
│   ├── ExampleController.php
│   └── HomeController.php
├── bootstrap.php
└── routes.php
```

`app/Admin/routes.php` はルート定義に使用されます。

`app/Admin/bootstrap.php` は laravel-admin のブートストラッパーです。使用例はファイル内のコメントを参照してください。

`app/Admin/Controllers` ディレクトリにはすべてのコントローラーが格納されます。
このディレクトリ内の `HomeController.php` は管理画面のホーム画面のリクエストを処理します。
`ExampleController.php` はコントローラーのサンプルです。

### 静的アセット

フロントエンドの静的ファイルは `/public/packages/admin` ディレクトリに配置されます。
