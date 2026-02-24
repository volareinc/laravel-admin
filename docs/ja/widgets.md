# ウィジェット

## Box

`Encore\Admin\Widgets\Box` は Box コンポーネントを生成するために使用します：

```php
use Encore\Admin\Widgets\Box;

$box = new Box('Box Title', 'Box content');

$box->removable();

$box->collapsable();

$box->style('info');

$box->solid();

echo $box;

```

`$content` パラメータは Box のコンテンツ要素で、`Illuminate\Contracts\Support\Renderable` インターフェースの実装、またはその他の出力可能な変数を指定できます。

`Box::title($title)` メソッドは、Box コンポーネントのタイトルを設定するために使用します。

`Box::content($content)` メソッドは、Box コンポーネントのコンテンツ要素を設定するために使用します。

`Box::removable()` メソッドは、Box コンポーネントを削除可能に設定します。

`Box::collapsable()` メソッドは、Box コンポーネントを折りたたみ可能に設定します。

`Box::style($style)` メソッドは、Box コンポーネントのスタイルを設定します。`primary`、`info`、`danger`、`warning`、`success`、`default` を指定できます。

`Box::solid()` メソッドは、Box コンポーネントに枠線を追加します。

## Collapse

`Encore\Admin\Widgets\Collapse` クラスは、折りたたみコンポーネントを生成するために使用します：
```php
use Encore\Admin\Widgets\Collapse;

$collapse = new Collapse();

$collapse->add('Bar', 'xxxxx');
$collapse->add('Orders', new Table());

echo $collapse->render();

```

`Collapse::add($title, $content)` メソッドは、折りたたみコンポーネントにアイテムを追加するために使用します。`$title` パラメータはアイテムのタイトルを設定します。`$content` パラメータはアイテムのコンテンツを設定します。


## Form

`Encore\Admin\Widgets\Form` クラスは、フォームを素早く構築するために使用します：

```php

$form = new Form();

$form->action('example');

$form->email('email')->default('qwe@aweq.com');
$form->password('password');
$form->text('name');
$form->url('url');
$form->color('color');
$form->map('lat', 'lng');
$form->date('date');
$form->json('val');
$form->dateRange('created_at', 'updated_at');

echo $form->render();
```

`Form::__construct($data = [])` はフォームオブジェクトを生成します。`$data` パラメータが渡された場合、`$data` 配列の要素がフォームに入力されます。

`Form::action($uri)` メソッドは、フォームの送信先アドレスを設定するために使用します。

`Form::method($method)` メソッドは、フォームの送信メソッドを設定するために使用します。デフォルトは `POST` メソッドです。

`Form::disablePjax()` はフォーム送信時の pjax を無効にします。

## InfoBox

`Encore\Admin\Widgets\InfoBox` クラスは、情報表示ブロックを生成するために使用します：

```php
use Encore\Admin\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/admin/users', '1024');

echo $infoBox->render();

```

`InfoBox` の使用方法については、ホームページレイアウトファイル [HomeController.php](https://github.com/z-song/laravel-admin/blob/master/src/Console/stubs/HomeController.stub) の `index()` メソッド内の `InfoBox` セクションを参照してください。

## Tab コンポーネント

`Encore\Admin\Widgets\Tab` クラスは、タブコンポーネントを生成するために使用します：

```php
use Encore\Admin\Widgets\Tab;

$tab = new Tab();

$tab->add('Pie', $pie);
$tab->add('Table', new Table());
$tab->add('Text', 'blablablabla....');

echo $tab->render();

```

`Tab::add($title, $content)` メソッドは新しいタブを追加するために使用します。`$title` はタブのタイトル、`$content` はタブのコンテンツです。

## Table

`Encore\Admin\Widgets\Table` クラスは、テーブルを生成するために使用します：

```php
use Encore\Admin\Widgets\Table;

// テーブル 1
$headers = ['Id', 'Email', 'Name', 'Company'];
$rows = [
    [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica'],
    [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar'],
    [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC'],
    [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor'],
    [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.'],
];

$table = new Table($headers, $rows);

echo $table->render();

// テーブル 2
$headers = ['Keys', 'Values'];
$rows = [
    'name'   => 'Joe',
    'age'    => 25,
    'gender' => 'Male',
    'birth'  => '1989-12-05',
];

$table = new Table($headers, $rows);

echo $table->render();

```
