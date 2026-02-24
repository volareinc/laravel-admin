# 行アクション

`model-grid` にはデフォルトで `edit` と `delete` の2つのアクションがあります。以下の方法で無効にできます:

```php
 $grid->actions(function ($actions) {
    $actions->disableDelete();
    $actions->disableEdit();
});
```
渡された `$actions` パラメータを使用して、現在の行のデータを取得できます:
```php
 $grid->actions(function ($actions) {

    // 現在の行のデータ配列
    $actions->row;

    // 現在の行の主キー値を取得
    $actions->getKey();
});
```

カスタムアクションボタンがある場合は、以下のように追加できます:

```php
$grid->actions(function ($actions) {

    // アクションを末尾に追加
    $actions->append('<a href=""><i class="fa fa-eye"></i></a>');

    // アクションを先頭に追加
    $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');
}
```

より複雑なアクションが必要な場合は、以下の方法を参考にしてください。

まず、アクションクラスを定義します:
```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class CheckRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {

    // あなたのコード
    console.log($(this).data('id'));

});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='{$this->id}'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}
```
次に、アクションを追加します:
```php
$grid->actions(function ($actions) {

    // アクションを追加
    $actions->append(new CheckRow($actions->getKey()));
}
```

カラム条件による行の操作:
行の属性には `$row->model()` 配列または `$row->column()` メソッドを使用できます。
属性を設定した後にスタイルを設定する必要があります。そうしないと、style メソッドがスキップされます。
```php
$grid->rows(function ($row) {
   // released カラムの値が Yes の場合
   if ( $row->column('released') == 'Yes' ) {
        // 行の属性を設定
        $row->setAttributes([ 'data-row-id' => $row->model()['id'], 'data-row-date' => $row->column('release_date') ]);
        // 行のスタイルを設定
        $row->style("background-color:green");
    }

});
```
