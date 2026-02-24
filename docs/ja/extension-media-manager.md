# メディアマネージャー

ローカルファイルを管理するためのツールです。

![wx20170809-170104](https://user-images.githubusercontent.com/1479100/29113762-99886c32-7d24-11e7-922d-5981a5849c7a.png)

## インストール

```
$ composer require laravel-admin-ext/media-manager -vvv

$ php artisan admin:import media-manager
```

## 設定

`config/admin.php` を開き、管理したいディスクを指定します：

```php

    'extensions' => [

        'media-manager' => [
            'disk' => 'public'   // config/filesystem.php で設定したディスクを指定します
        ],
    ],

```

`disk` は `config/filesystem.php` で設定したローカルディスクです。`http://localhost/admin/media` にアクセスして使用できます。

ディスク内の画像をプレビューしたい場合は、ディスク設定にアクセス URL を設定する必要があります：


`config/filesystem.php`：
```php

    'disks' => [

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',    // URL を設定
            'visibility' => 'public',
        ],

        ...
    ]
```
