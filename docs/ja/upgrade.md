# アップグレード注意事項

`laravel-admin 1.5` では組み込みのテーブル構造が変更されているため、`laravel 5.5` と `laravel-admin 1.5` を再インストールし、コードを移行することを推奨します。

コード移行時の注意点：

- テーブル構造の変更については [tables.php](https://github.com/z-song/laravel-admin/blob/master/database/migrations/2016_01_04_173148_create_admin_tables.php) を参照してください
- ルーティングファイルの構造が変更されています。[routes.stub](https://github.com/z-song/laravel-admin/blob/master/src/Console/stubs/routes.stub) を参照してください
- 設定ファイルの構造変更については [admin.php](https://github.com/z-song/laravel-admin/blob/master/config/admin.php) を参照してください
- チャートコンポーネントは削除されたため、使用できなくなりました。[カスタムチャート](/ja/custom-chart.md) を参照してください
