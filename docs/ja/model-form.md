# データフォーム (Model Form)

`Encore\Admin\Form` クラスは、データモデルに基づいたフォームを生成するために使用されます。例えば、データベースに `movies` テーブルがある場合:

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

対応するデータモデルは `App\Models\Movie` であり、以下のコードで `movies` のデータフォームを生成できます:

```php

use App\Models\Movie;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;

$grid = Admin::form(Movie::class, function(Form $grid){

    // レコードIDを表示
    $form->display('id', 'ID');

    // テキスト型の入力ボックスを追加
    $form->text('title', 'Movie title');

    $directors = [
        1 => 'John',
        2 => 'Smith',
        3 => 'Kate',
    ];

    $form->select('director', 'Director')->options($directors);

    // describe フィールド用のテキストエリアを追加
    $form->textarea('describe', 'Describe');

    // 数値入力
    $form->number('rate', 'Rate');

    // スイッチフィールドを追加
    $form->switch('released', 'Released?');

    // 日時選択ボックスを追加
    $form->dateTime('release_at', 'release time');

    // 2つの日時カラムを表示
    $form->display('created_at', 'Created time');
    $form->display('updated_at', 'Updated time');
});

```

## カスタムツール

フォームの右上隅にはデフォルトで2つのボタンツールがあります。以下の方法で変更できます:

```php
$form->tools(function (Form\Tools $tools) {

    // 戻るボタンを無効化
    $tools->disableBackButton();

    // 一覧ボタンを無効化
    $tools->disableListButton();

    // ボタンを追加。引数は文字列、または Renderable もしくは Htmlable インターフェースを実装したオブジェクトのインスタンス
    $tools->add('<a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;delete</a>');
});
```

## その他のメソッド

送信ボタンを無効化:

```php
$form->disableSubmit();
```

リセットボタンを無効化:
```php
$form->disableReset();
```

保存時に無視するフィールドを指定:
```php
$form->ignore('column1', 'column2', 'column3');
```

ラベルとフィールドの幅を設定:

```php
$form->setWidth(10, 2);
```

フォームのアクションを設定:

```php
$form->setAction('admin/users');
```

## モデルのリレーション


### 1対1 (One to One)
`users` テーブルと `profiles` テーブルは、`profiles.user_id` フィールドを通じて1対1のリレーションを持ちます。

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

以下のコードでフォーム内でリレーションを関連付けることができます:

```php
Admin::form(User::class, function (Form $form) {

    $form->display('id');

    $form->text('name');
    $form->text('email');

    $form->text('profile.age');
    $form->text('profile.gender');

    $form->datetime('created_at');
    $form->datetime('updated_at');
});

```
