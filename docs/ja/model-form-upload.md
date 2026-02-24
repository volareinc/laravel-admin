# ファイル/画像アップロード

[model-form](/ja/model-form.md) では、以下のコードでファイルおよび画像アップロードフィールドを構築できます。

```php
$form->file('file_column');
$form->image('image_column');
```

### 保存パスとファイル名の変更

```php

// アップロードパスを変更
$form->image('picture')->move('public/upload/image1/');

// ユニークなファイル名を使用 (md5(uniqid()).拡張子)
$form->image('picture')->uniqueName();

// ファイル名を指定
$form->image('picture')->name(function ($file) {
    return 'test.'.$file->guessExtension();
});

```

[model-form](/ja/model-form.md) はローカルストレージとクラウドストレージの両方のアップロードに対応しています。

### ローカルへのアップロード

まず、ストレージの設定を追加します。`config/filesystems.php` にディスクを追加してください:

```php

'disks' => [
    ... ,

    'admin' => [
        'driver' => 'local',
        'root' => public_path('uploads'),
        'visibility' => 'public',
        'url' => env('APP_URL').'/uploads',
    ],
],

```

アップロードパスを `public/upload`(public_path('upload'))に設定します。

次に、`config/admin.php` で上記で設定した `disk` を選択します:

```php

'upload'  => [

    'disk' => 'admin',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],
],

```

`disk` を上記で追加した `admin` に設定します。`directory.image` と `directory.file` は、それぞれ `$form->image($column)` と `$form->file($column)` のアップロードパスです。

`host` はアップロードされたファイルのURLプレフィックスです。


### クラウドへのアップロード

クラウドストレージにアップロードする場合は、`flysystem` アダプターをサポートするドライバーをインストールする必要があります。ここでは `qiniu` クラウドストレージを例に説明します。

まず [zgldh/qiniu-laravel-storage](https://github.com/zgldh/qiniu-laravel-storage) をインストールします。

同様にディスクの設定を行います。`config/filesystems.php` に項目を追加します:

```php
'disks' => [
    ... ,
    'qiniu' => [
        'driver'  => 'qiniu',
        'domains' => [
            'default'   => 'xxxxx.com1.z0.glb.clouddn.com',
            'https'     => 'dn-yourdomain.qbox.me',
            'custom'    => 'static.abc.com',
         ],
        'access_key'=> '',  //AccessKey
        'secret_key'=> '',  //SecretKey
        'bucket'    => '',  //Bucket
        'notify_url'=> '',  //
        'url'       => 'http://of8kfibjo.bkt.clouddn.com/',
    ],
],

```

次に、`laravel-admin` のアップロード設定を変更します。`config/admin.php` を開き、以下の箇所を見つけます:

```php

'upload'  => [

    'disk' => 'qiniu',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],
],

```

`disk` に上記で設定した `qiniu` を選択します。
