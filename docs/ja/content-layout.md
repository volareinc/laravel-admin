# ページコンテンツとレイアウト

`laravel-admin` のレイアウトの使い方は、ホームページのレイアウトファイル [HomeController.php](https://github.com/z-song/laravel-admin/blob/master/src/Console/stubs/HomeController.stub) の `index()` メソッドで確認できます。

`Encore\Admin\Layout\Content` クラスは、コンテンツ領域のレイアウトを実装するために使用されます。`Content::body($element)` メソッドを使用してページコンテンツを追加します。

未入力のコンテンツのページコードは以下の通りです:

```php
public function index()
{
    return Admin::content(function (Content $content) {

        // 任意
        $content->header('page header');

        // 任意
        $content->description('page description');

        // v1.5.7 からパンくずリストを追加
        $content->breadcrumb(
            ['text' => 'Dashboard', 'url' => '/admin'],
            ['text' => 'User management', 'url' => '/admin/users'],
            ['text' => 'Edit user']
        );

        // ページの body 部分を埋めます。レンダリング可能な任意のオブジェクトをここに配置できます
        $content->body('hello world');
    });
}
```

メソッド `$content->body();` は、文字列、数値、`__toString` メソッドを持つクラス、または `Renderable`、`Htmlable` インターフェースを実装するクラスなど、レンダリング可能な任意のオブジェクトを受け入れます。Laravel の View オブジェクトも含まれます。

## レイアウト

`laravel-admin` は Bootstrap のグリッドシステムを使用しており、各行の長さは 12 です。以下にいくつかの簡単な例を示します:

コンテンツの1行を追加する:

```php
$content->row('hello')

---------------------------------
|hello                          |
|                               |
|                               |
|                               |
|                               |
|                               |
---------------------------------

```

行内に複数のカラムを追加する:

```php
$content->row(function(Row $row) {
    $row->column(4, 'foo');
    $row->column(4, 'bar');
    $row->column(4, 'baz');
});
----------------------------------
|foo       |bar       |baz       |
|          |          |          |
|          |          |          |
|          |          |          |
|          |          |          |
|          |          |          |
----------------------------------


$content->row(function(Row $row) {
    $row->column(4, 'foo');
    $row->column(8, 'bar');
});
----------------------------------
|foo       |bar                  |
|          |                     |
|          |                     |
|          |                     |
|          |                     |
|          |                     |
----------------------------------

```

カラムの中にカラムを配置する:

```php
$content->row(function (Row $row) {

    $row->column(4, 'xxx');

    $row->column(8, function (Column $column) {
        $column->row('111');
        $column->row('222');
        $column->row('333');
    });
});
----------------------------------
|xxx       |111                  |
|          |---------------------|
|          |222                  |
|          |---------------------|
|          |333                  |
|          |                     |
----------------------------------


```


行の中に行を追加し、さらにカラムを追加する:

```php
$content->row(function (Row $row) {

    $row->column(4, 'xxx');

    $row->column(8, function (Column $column) {
        $column->row('111');
        $column->row('222');
        $column->row(function(Row $row) {
            $row->column(6, '444');
            $row->column(6, '555');
        });
    });
});
----------------------------------
|xxx       |111                  |
|          |---------------------|
|          |222                  |
|          |---------------------|
|          |444      |555        |
|          |         |           |
----------------------------------
```

ページに body を追加する:

`/project/resources/views/admin/custom.blade.php` に blade ビューファイルを作成します。

```php
    public function customPage($id)
    {
        $content = new Content();
        $content->header('View');
        $content->description('Description...');
        $content->body('admin.custom',['id' => $id]);
        return $content;
    }
```
