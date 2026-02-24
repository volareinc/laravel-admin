# カスタム認証

`laravel-admin` 組み込みの認証ログインロジックを使用しない場合は、以下の方法を参考にしてログイン認証ロジックをカスタマイズできます。

まず、ユーザーの身元情報を取得するための `User provider` を定義する必要があります。例えば `app/Providers/CustomUserProvider.php` を作成します：

```php
<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class CustomUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {}

    public function retrieveByToken($identifier, $token)
    {}

    public function updateRememberToken(Authenticatable $user, $token)
    {}

    public function retrieveByCredentials(array $credentials)
    {
        // $credentials を使用してユーザーデータを取得し、`Illuminate\Contracts\Auth\Authenticatable` インターフェースを実装したオブジェクトを返します
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // $credentials 内のユーザー名とパスワードでユーザーを検証し、`true` または `false` を返します
    }
}

```

`retrieveByCredentials` メソッドと `validateCredentials` メソッドのパラメータ `$credentials` は、ログインページから送信されたユーザー名とパスワードの配列です。`$credentials` を使用して独自のログインロジックを実装できます。

`Illuminate\Contracts\Auth\Authenticatable` インターフェースの定義：
```php
<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable {

    public function getAuthIdentifierName();
    public function getAuthIdentifier();
    public function getAuthPassword();
    public function getRememberToken();
    public function setRememberToken($value);
    public function getRememberTokenName();

}
```

カスタム認証の詳細については [adding-custom-user-providers](https://laravel.com/docs/5.5/authentication#adding-custom-user-providers) を参照してください。


カスタム User provider を作成したら、Laravel に登録する必要があります：

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('custom', function ($app, array $config) {

            // Illuminate\Contracts\Auth\UserProvider のインスタンスを返します...
            return new CustomUserProvider();
        });
    }
}
```

最後に設定を変更します。`config/admin.php` を開き、`auth` セクションを探します：

```php
    'auth' => [
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ]
        ],

        // 以下を変更します
        'providers' => [
            'admin' => [
                'driver' => 'custom',
            ]
        ],
    ],
```
以上でカスタム認証のロジックが完成します。
