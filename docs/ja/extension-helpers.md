# ヘルパーツール

開発者向けのサポートツールです。開発時の効率向上に役立ちます。現在、`scaffold（スキャフォールド）`、`database command line（データベースコマンドライン）`、`artisan command line（artisan コマンドライン）` の3つのツールを提供しています。より良いユーティリティのアイデアがあれば、ぜひご提案ください。

インストール：
```php
composer require laravel-admin-ext/helpers

php artisan admin:import helpers
```

> 一部のツール機能はプロジェクト内でファイルの作成や削除を行うため、ファイルやディレクトリの権限エラーが発生する場合があります。その場合は権限の問題を解決する必要があります。
> また、データベースや artisan コマンドの一部は Web 環境では使用できない場合があります。

## Scaffold

このツールは、コントローラー、モデル、マイグレーションファイルの構築と、マイグレーションの実行を支援します。`http://localhost/admin/helpers/scaffold` にアクセスして使用できます。

マイグレーションのテーブル構造を設定する際、主キーフィールドは自動的に生成されるため、入力する必要はありません。

![qq20170220-2](https://cloud.githubusercontent.com/assets/1479100/23147949/cbf03e84-f81d-11e6-82b7-d7929c3033a0.png)

## データベースコマンドライン

Web に統合されたデータベースコマンドラインツールです。現在 `mysql`、`mongodb`、`redis` をサポートしています。`http://localhost/admin/helpers/terminal/database` にアクセスして使用できます。

右上隅でデータベース接続を変更し、下部の入力ボックスに対応するデータベースクエリを入力して Enter を押すと、クエリ結果を取得できます。

![qq20170220-3](https://cloud.githubusercontent.com/assets/1479100/23147951/ce08e5d6-f81d-11e6-8b20-605e8cd06167.png)

データベースの使い方は通常のデータベース操作と同じで、選択したデータベースがサポートするクエリを実行できます。

## Artisan コマンドライン

`Laravel` の `artisan` コマンドラインの Web 版です。ここで artisan コマンドを実行できます。`http://localhost/admin/helpers/terminal/artisan` にアクセスして使用できます。

![qq20170220-1](https://cloud.githubusercontent.com/assets/1479100/23147963/da8a5d30-f81d-11e6-97b9-239eea900ad3.png)


## ルート一覧

このツールは、URI、HTTP メソッド、ミドルウェアを含むすべてのルートをより直感的に表示します。ルートの検索も可能です。`http://localhost/admin/helpers/routes` にアクセスして使用できます。

![helpers_routes](https://user-images.githubusercontent.com/1479100/30899066-e8bdd5ca-a390-11e7-809d-4ceccd0da27f.png)
