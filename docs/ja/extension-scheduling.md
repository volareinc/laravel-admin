# タスクスケジュール

このツールは、Laravel のスケジュールされたタスクを管理するための Web インターフェースです。

![wx20170810-101048](https://user-images.githubusercontent.com/1479100/29151552-8affc0b2-7db4-11e7-932a-a10d8a42ec50.png)

## インストール

```
$ composer require laravel-admin-ext/scheduling -vvv

$ php artisan admin:import scheduling
```

インストール後、`http://localhost/admin/scheduling` を開きます。

## タスクの追加

`app/Console/Kernel.php` を開き、スケジュールされたタスクを追加してみましょう：

```php
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->everyTenMinutes();

        $schedule->command('route:list')->dailyAt('02:00');
    }
}

```

ページにタスクの詳細が表示され、ページから直接これらのタスクを実行することもできます。
