# laravel-admin

[![Build Status](https://travis-ci.org/z-song/laravel-admin.svg?branch=master)](https://travis-ci.org/z-song/laravel-admin)
[![StyleCI](https://styleci.io/repos/48796179/shield)](https://styleci.io/repos/48796179)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/z-song/laravel-admin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/z-song/laravel-admin/?branch=master)
[![Packagist](https://img.shields.io/packagist/l/volareinc/laravel-admin.svg?maxAge=2592000)](https://packagist.org/packages/volareinc/laravel-admin)
[![Total Downloads](https://img.shields.io/packagist/dt/volareinc/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/volareinc/laravel-admin)
[![Awesome Laravel](https://img.shields.io/badge/Awesome-Laravel-brightgreen.svg)](https://github.com/z-song/laravel-admin)

`laravel-admin` は Laravel 用の管理画面ビルダーです。わずか数行のコードで CRUD バックエンドを構築できます。

> このパッケージは [z-song/laravel-admin](https://github.com/z-song/laravel-admin) のフォーク版です。

[デモ](http://laravel-admin.org/demo) ユーザー名/パスワード: `admin/admin`

[SleepingOwlAdmin](https://github.com/sleeping-owl/admin) と [rapyd-laravel](https://github.com/zofe/rapyd-laravel) にインスパイアされています。

[英語ドキュメント](http://laravel-admin.org/docs) | [中文文档](http://laravel-admin.org/docs/#/zh/) | [日本語ドキュメント](/ja/)

## 主な機能

- CRUD のための Model Grid / Model Form / Model Tree を内蔵
- 豊富なフォームフィールド（テキスト、セレクト、日時ピッカー、画像・ファイルアップロードなど）
- 権限管理（RBAC）
- メニュー管理
- 管理画面用の多数のウィジェット
- 拡張可能なアーキテクチャ

## スクリーンショット

![laravel-admin](https://cloud.githubusercontent.com/assets/1479100/19625297/3b3deb64-9947-11e6-807c-cffa999004be.jpg)

## インストール

> このパッケージは PHP 7 以上および Laravel 5.5 が必要です。旧バージョンについては [1.4](http://laravel-admin.org/docs/v1.4/#/) を参照してください。

まず、Laravel 5.5 をインストールし、データベース接続の設定が正しいことを確認してください。

```
composer require volareinc/laravel-admin
```

次に、以下のコマンドでアセットと設定ファイルを公開します：

```
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
```

コマンド実行後、設定ファイルが `config/admin.php` に生成されます。このファイルでインストールディレクトリ、データベース接続、テーブル名などを変更できます。

最後に、以下のコマンドを実行してインストールを完了します：

```
php artisan admin:install
```

ブラウザで `http://localhost/admin/` を開き、ユーザー名 `admin`、パスワード `admin` でログインしてください。

## デフォルト設定

`config/admin.php` ファイルにすべての設定項目が含まれています。デフォルト設定はこのファイルで確認できます。

## 依存パッケージ・サービス

`laravel-admin` は以下のプラグインやサービスに依存しています：

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)
+ [Nestable](http://dbushell.github.io/Nestable/)
+ [toastr](http://codeseven.github.io/toastr/)
+ [X-editable](http://github.com/vitalets/x-editable)
+ [bootstrap-number-input](https://github.com/wpic/bootstrap-number-input)
+ [fontawesome-iconpicker](https://github.com/itsjavi/fontawesome-iconpicker)

## ライセンス

`laravel-admin` は [MIT ライセンス (MIT)](LICENSE) の下でライセンスされています。
