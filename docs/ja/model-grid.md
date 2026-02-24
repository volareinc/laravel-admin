# データテーブル (Model Grid)

クラス `Encore\Admin\Grid` は、データモデルに基づいてテーブルを生成するために使用されます。例えば、データベースに `movies` テーブルがあるとします:

```sql
CREATE TABLE `movies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(10) unsigned NOT NULL,
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` tinyint unsigned NOT NULL,
  `released` enum(0, 1),
  `release_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

```

このテーブルのモデルは `App\Models\Movie` です。以下のコードで `movies` テーブルのデータグリッドを生成できます:

```php

use App\Models\Movie;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;

$grid = Admin::grid(Movie::class, function(Grid $grid){

    // 最初のカラムは id フィールドを表示し、ソート可能なカラムとして設定します
    $grid->id('ID')->sortable();

    // 2番目のカラムは title フィールドを表示します。title フィールド名と Grid オブジェクトの title メソッドが競合するため、Grid の column() メソッドを代わりに使用します
    $grid->column('title');

    // 3番目のカラムは director フィールドを表示します。display($callback) メソッドにより、users テーブルの対応するユーザー名を表示するように設定しています
    $grid->director()->display(function($userId) {
        return User::find($userId)->name;
    });

    // 4番目のカラムは describe フィールドとして表示されます
    $grid->describe();

    // 5番目のカラムは rate フィールドとして表示されます
    $grid->rate();

    // 6番目のカラムは released フィールドを表示し、display($callback) メソッドで表示出力をフォーマットします
    $grid->released('Release?')->display(function ($released) {
        return $released ? 'yes' : 'no';
    });

    // 以下は3つの日時フィールドのカラムを表示します
    $grid->release_at();
    $grid->created_at();
    $grid->updated_at();

    // filter($callback) メソッドは、テーブルのシンプルな検索ボックスを設定するために使用されます
    $grid->filter(function ($filter) {

        // created_at フィールドの範囲クエリを設定します
        $filter->between('created_at', 'Created Time')->datetime();
    });
});

```

## 基本的な使い方

#### カラムの追加
```php

// フィールド名 `username` で直接カラムを追加します
$grid->username('Username');

// 上記と同じ効果です
$grid->column('username', 'Username');

// 複数のカラムを追加します
$grid->columns('email', 'username' ...);
```

#### ソースデータの変更
```php
$grid->model()->where('id', '>', 100);

$grid->model()->orderBy('id', 'desc');

$grid->model()->take(100);

```

#### 1ページあたりの表示行数の設定

```php
// デフォルトは1ページあたり15件です
$grid->paginate(20);
```

#### カラムの表示出力を変更する

```php
use Illuminate\Support\Str;
$grid->text()->display(function($text) {
    return Str::limit($text, 30, '...');
});

$grid->name()->display(function ($name) {
    return "<span class='label'>$name</span>";
});

$grid->email()->display(function ($email) {
    return "mailto:$email";
});

// テーブルに存在しないカラム
$grid->column('column_not_in_table')->display(function () {
    return 'blablabla....';
});

```

`display()` メソッドに渡されるクロージャは行データオブジェクトにバインドされているため、現在の行の他のカラムデータを使用できます。

```php
$grid->first_name();
$grid->last_name();

// テーブルに存在しないカラム
$grid->column('full_name')->display(function () {
    return $this->first_name.' '.$this->last_name;
});
```

#### 新規作成ボタンを無効にする
```php
$grid->disableCreateButton();
```

#### ページネーションを無効にする
```php
$grid->disablePagination();
```

#### グリッドヘッダーのすべてのツールを無効にする（フィルター、リフレッシュ、エクスポート、一括操作）
```php
$grid->disableTools();
```

#### データフィルターを無効にする
```php
$grid->disableFilter();
```

#### エクスポートボタンを無効にする
```php
$grid->disableExport();
```

#### 行セレクターを無効にする
```php
$grid->disableRowSelector();
```

#### 行アクションを無効にする
```php
$grid->disableActions();
```

#### 並べ替え可能なグリッドを有効にする
```php
$grid->orderable();
```

#### perPage セレクターのオプションを設定する
```php
$grid->perPages([10, 20, 30, 40, 50]);
```

## リレーション


### 1対1 (One to One)

`users` テーブルと `profiles` テーブルは、`profiles.user_id` フィールドを通じて1対1のリレーションを生成します。

```sql

CREATE TABLE `users` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `profiles` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

対応するデータモデルは以下の通りです:

```php

class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

```

以下のコードでグリッド内でリレーションを関連付けることができます:

```php
Admin::grid(User::class, function (Grid $grid) {

    $grid->id('ID')->sortable();

    $grid->name();
    $grid->email();

    $grid->column('profile.age');
    $grid->column('profile.gender');

    //または
    $grid->profile()->age();
    $grid->profile()->gender();

    $grid->created_at();
    $grid->updated_at();
});

```

### 1対多 (One to Many)

`posts` テーブルと `comments` テーブルは、`comments.post_id` フィールドを通じて1対多のリレーションを生成します。

```sql

CREATE TABLE `posts` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `comments` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`post_id` int(10) unsigned NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

対応するデータモデルは以下の通りです:

```php

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

```

以下のコードでグリッド内でリレーションを関連付けることができます:

```php

return Admin::grid(Post::class, function (Grid $grid) {
    $grid->id('id')->sortable();
    $grid->title();
    $grid->content();

    $grid->comments('Comments count')->display(function ($comments) {
        $count = count($comments);
        return "<span class='label label-warning'>{$count}</span>";
    });

    $grid->created_at();
    $grid->updated_at();
});


return Admin::grid(Comment::class, function (Grid $grid) {
    $grid->id('id');
    $grid->post()->title();
    $grid->content();

    $grid->created_at()->sortable();
    $grid->updated_at();
});

```

### 多対多 (Many to Many)

`users` テーブルと `roles` テーブルは、中間テーブル `role_user` を通じて多対多のリレーションを生成します。

```sql

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```

対応するデータモデルは以下の通りです:

```php

class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

```

以下のコードでグリッド内でリレーションを関連付けることができます:

```php
return Admin::grid(User::class, function (Grid $grid) {
    $grid->id('ID')->sortable();
    $grid->username();
    $grid->name();

    $grid->roles()->display(function ($roles) {

        $roles = array_map(function ($role) {
            return "<span class='label label-success'>{$role['name']}</span>";
        }, $roles);

        return join('&nbsp;', $roles);
    });

    $grid->created_at();
    $grid->updated_at();
});

```
