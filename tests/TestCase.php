<?php

use Encore\Admin\AdminServiceProvider;
use Encore\Admin\Facades\Admin;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost:8000';

    /**
     * Testbench が組み立てるアプリケーションに、laravel-admin を登録する。
     */
    protected function getPackageProviders($app)
    {
        return [AdminServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return ['Admin' => Admin::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $adminConfig = require __DIR__.'/config/admin.php';

        $this->app['config']->set('database.default', env('DB_CONNECTION', 'mysql'));
        $this->app['config']->set('database.connections.sqlite.database', env('DB_DATABASE', ':memory:'));
        $this->app['config']->set('database.connections.mysql.host', env('MYSQL_HOST', 'localhost'));
        $this->app['config']->set('database.connections.mysql.database', env('MYSQL_DATABASE', 'laravel_admin_test'));
        $this->app['config']->set('database.connections.mysql.username', env('MYSQL_USER', 'root'));
        $this->app['config']->set('database.connections.mysql.password', env('MYSQL_PASSWORD', ''));
        $this->app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $this->app['config']->set('filesystems', require __DIR__.'/config/filesystems.php');
        $this->app['config']->set('admin', $adminConfig);

        foreach (Arr::dot(Arr::get($adminConfig, 'auth'), 'auth.') as $key => $value) {
            $this->app['config']->set($key, $value);
        }

        $this->artisan('vendor:publish', ['--provider' => 'Encore\Admin\AdminServiceProvider']);

        Schema::defaultStringLength(191);

        $this->artisan('admin:install');

        $this->registerApplicationAutoloader();

        $this->migrateTestTables();

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;
        }

        require __DIR__.'/routes.php';

//        \Encore\Admin\Admin::$css = [];
//        \Encore\Admin\Admin::$js = [];
//        \Encore\Admin\Admin::$script = [];
    }

    protected function tearDown(): void
    {
        (new CreateAdminTables())->down();

        (new CreateTestTables())->down();

        DB::select("delete from `migrations` where `migration` = '2016_01_04_173148_create_admin_tables'");

        parent::tearDown();
    }

    /**
     * admin:install が Testbench のアプリケーションに生成する App\\ 配下のクラス
     * （App\\Admin\\Controllers\\HomeController など）を読み込めるようにする。
     * Testbench のアプリケーションは Composer の自動読み込みの対象外のため。
     */
    protected function registerApplicationAutoloader()
    {
        static $registered = false;

        if ($registered) {
            return;
        }

        spl_autoload_register(function ($class) {
            if (strncmp($class, 'App\\', 4) !== 0) {
                return;
            }

            $file = app_path(str_replace('\\', '/', substr($class, 4)).'.php');

            if (is_file($file)) {
                require_once $file;
            }
        });

        $registered = true;
    }

    /**
     * run package database migrations.
     *
     * @return void
     */
    public function migrateTestTables()
    {
        $fileSystem = new Filesystem();

        $fileSystem->requireOnce(__DIR__.'/migrations/2016_11_22_093148_create_test_tables.php');

        (new CreateTestTables())->up();
    }
}
