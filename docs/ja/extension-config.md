# 設定管理

このツールは設定データをデータベースに保存します。

![wx20170810-100226](https://user-images.githubusercontent.com/1479100/29151322-0879681a-7db3-11e7-8005-03310686c884.png)

## インストール

```
$ composer require laravel-admin-ext/config

$ php artisan migrate
```

`app/Providers/AppServiceProvider.php` を開き、`boot` メソッド内で `Config::load()` メソッドを呼び出します：

```php
<?php

namespace App\Providers;

use Encore\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Config::load();  // この行を追加
    }
}
```

次に、以下のコマンドを実行してメニューと権限をインポートします（手動で追加することもできます）：

```
$ php artisan admin:import config
```

`http://localhost/admin/config` を開きます。

## 使い方

管理パネルで設定を追加した後、`config($key)` を使用して設定した値を取得できます。
