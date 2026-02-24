# カラム操作

`model-grid` にはカラムを操作するための多くの機能が組み込まれており、これらのメソッドを使用してカラムデータを非常に柔軟に操作できます。

`Encore\Admin\Grid\Column` オブジェクトには、渡されたコールバック関数を通じて現在のカラムの値を処理するための `display()` メソッドが組み込まれています:
```php
$grid->column('title')->display(function ($title) {

    return "<span style='color:blue'>$title</span>";

});
```

`display` のコールバックは、現在の行データオブジェクトを親オブジェクトとしてバインドしているため、以下の方法で現在の行のデータを使用できます:
```php

$grid->first_name();

$grid->last_name();

$grid->column('full_name')->display(function () {
    return $this->first_name . ' ' . $this->last_name;
});
```

> メソッド `value()` は `display()` メソッドのエイリアスです。

## 組み込みメソッド

`model-grid` には、カラム機能を拡張するための組み込みメソッドがあります。

### editable

`editable.js` の助けを借りて、グリッド内のデータを直接編集できます:
```php
$grid->title()->editable();

$grid->title()->editable('textarea');

$grid->title()->editable('select', [1 => 'option1', 2 => 'option2', 3 => 'option3']);

$grid->birth()->editable('date');

$grid->published_at()->editable('datetime');

$grid->column('year')->editable('year');

$grid->column('month')->editable('month');

$grid->column('day')->editable('day');

```

### switch

> 注意: グリッドのカラムに switch を設定した場合、フォームでも同じカラムに switch を設定する必要があります。

以下のメソッドを使用して、カラムをすばやく switch コンポーネントに変換できます:
```php
$grid->status()->switch();

// `text`、`color`、`value` を設定
$states = [
    'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
    'off' => ['value' => 2, 'text' => 'NO', 'color' => 'default'],
];
$grid->status()->switch($states);

```

### switchGroup

> 注意: グリッドの複数のカラムに switch を設定した場合、フォームでもそれらのカラムに同じ switch を設定する必要があります。

以下のメソッドを使用して、カラムをすばやく switch コンポーネントグループに変換できます:
```php
$states = [
    'on' => ['text' => 'YES'],
    'off' => ['text' => 'NO'],
];

$grid->column('switch_group')->switchGroup([
    'hot'       => 'Hot',
    'new'       => 'New',
    'recommend' => 'Recommend',
], $states);

```

### select

```php
$grid->options()->select([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```

### radio
```php
$grid->options()->radio([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```

### checkbox
```php
$grid->options()->checkbox([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```

### image

```php
$grid->picture()->image();

// ホスト、幅、高さを設定
$grid->picture()->image('http://xxx.com', 100, 100);

// 複数の画像を表示
$grid->pictures()->display(function ($pictures) {

    return json_decode($pictures, true);

})->image('http://xxx.com', 100, 100);
```

### label
```php
$grid->name()->label();

// 色を設定。デフォルトは `success`、その他のオプション: `danger`、`warning`、`info`、`primary`、`default`、`success`
$grid->name()->label('danger');

// 配列も処理可能
$grid->keywords()->label();
```

### badge

```php
$grid->name()->badge();

// 色を設定。デフォルトは `success`、その他のオプション: `danger`、`warning`、`info`、`primary`、`default`、`success`
$grid->name()->badge('danger');

// 配列も処理可能
$grid->keywords()->badge();
```

## カラムの拡張

カラム機能を拡張するには2つの方法があります。1つ目は無名関数を使用する方法です。

`app/Admin/bootstrap.php` に以下のコードを追加します:
```php
use Encore\Admin\Grid\Column;

Column::extend('color', function ($value, $color) {
    return "<span style='color: $color'>$value</span>";
});
```
`model-grid` でこの拡張を使用します:
```php

$grid->title()->color('#ccc');

```

カラムの表示ロジックがより複雑な場合は、拡張クラスで実装できます。

拡張クラス `app/Admin/Extensions/Popover.php`:
```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Popover extends AbstractDisplayer
{
    public function display($placement = 'left')
    {
        Admin::script("$('[data-toggle=\"popover\"]').popover()");

        return <<<EOT
<button type="button"
    class="btn btn-secondary"
    title="popover"
    data-container="body"
    data-toggle="popover"
    data-placement="$placement"
    data-content="{$this->value}"
    >
  Popover
</button>

EOT;

    }
}
```
次に `app/Admin/bootstrap.php` で拡張を登録します:
```php
use Encore\Admin\Grid\Column;
use App\Admin\Extensions\Popover;

Column::extend('popover', Popover::class);
```
`model-grid` で拡張を使用します:
```php
$grid->desciption()->popover('right');
```


## ヘルパー
### 文字列操作
現在の出力データが文字列の場合、`Illuminate\Support\Str` クラスのメソッドを呼び出すことができます。

例えば、以下のカラムは `title` フィールドの文字列値を表示します:

```php
$grid->title();
```

`title` カラムで `Str::limit()` を呼び出します。

`title` カラムの出力文字列に対して `Str::limit()` メソッドを呼び出すことができます。

```php
$grid->title()->limit(30);
```

`Illuminate\Support\Str` のメソッドを続けて呼び出します:

```php
$grid->title()->limit(30)->ucfirst();

$grid->title()->limit(30)->ucfirst()->substr(1, 10);

```

### 配列操作
現在の出力データが配列の場合、`Illuminate\Support\Collection` クラスのメソッドを呼び出すことができます。

例えば、`tags` カラムは1対多のリレーションから取得された配列データです:
```php
$grid->tags();

array (
  0 =>
  array (
    'id' => '16',
    'name' => 'php',
    'created_at' => '2016-11-13 14:03:03',
    'updated_at' => '2016-12-25 04:29:35',

  ),
  1 =>
  array (
    'id' => '17',
    'name' => 'python',
    'created_at' => '2016-11-13 14:03:09',
    'updated_at' => '2016-12-25 04:30:27',
  ),
)

```

`Collection::pluck()` メソッドを呼び出して、配列から `name` カラムを取得します:
```php
$grid->tags()->pluck('name');

array (
    0 => 'php',
    1 => 'python',
  ),

```

上記の処理後も出力データは配列のままなので、引き続き `Illuminate\Support\Collection` のメソッドを呼び出すことができます。

```php
$grid->tags()->pluck('name')->map('ucwords');

array (
    0 => 'Php',
    1 => 'Python',
  ),
```
配列を文字列として出力します:
```php
$grid->tags()->pluck('name')->map('ucwords')->implode('-');

"Php-Python"
```

### 混合使用

上記の2種類のメソッド呼び出しにおいて、前のステップの出力が値の型を確定できる限り、対応する型のメソッドを呼び出すことができるため、非常に柔軟に組み合わせることができます。

例えば、`images` フィールドは複数の画像アドレスの配列を格納するJSON形式の文字列型です:

```php

$grid->images();

"['foo.jpg', 'bar.png']"

// メソッドチェーンで複数の画像を表示
$grid->images()->display(function ($images) {

    return json_decode($images, true);

})->map(function ($path) {

    return 'http://localhost/images/'. $path;

})->image();

```
