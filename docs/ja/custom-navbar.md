# ナビゲーションバーのカスタマイズ

バージョン `1.5.6` 以降、上部ナビゲーションバーに HTML 要素を追加できるようになりました。`app/Admin/bootstrap.php` を開きます：
```php
use Encore\Admin\Facades\Admin;

Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {

    $navbar->left('html...');

    $navbar->right('html...');

});
```

`left` メソッドと `right` メソッドは、ヘッダーの左側と右側にコンテンツを追加するために使用します。メソッドのパラメータには、レンダリング可能なオブジェクト（`Htmlable`、`Renderable` インターフェースを実装しているオブジェクト、または `__toString()` メソッドを持つオブジェクト）や文字列を指定できます。

## 左側に要素を追加する

例えば、左側に検索バーを追加する場合、まずビュー `resources/views/search-bar.blade.php` を作成します：
```php
<style>

.search-form {
    width: 250px;
    margin: 10px 0 0 20px;
    border-radius: 3px;
    float: left;
}
.search-form input[type="text"] {
    color: #666;
    border: 0;
}

.search-form .btn {
    color: #999;
    background-color: #fff;
    border: 0;
}

</style>

<form action="/admin/posts" method="get" class="search-form" pjax-container>
    <div class="input-group input-group-sm ">
        <input type="text" name="title" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span>
    </div>
</form>
```
次に、ナビゲーションバーに追加します：
```php
$navbar->left(view('search-bar'));
```

## 右側に要素を追加する

ナビゲーションバーの右側には `<li>` タグのみを追加できます。例えば、通知アイコンを追加するには、新しいレンダリングクラス `app/Admin/Extensions/Nav/Links.php` を作成します：
```php
<?php

namespace App\Admin\Extensions\Nav;

class Links
{
    public function __toString()
    {
        return <<<HTML

<li>
    <a href="#">
      <i class="fa fa-envelope-o"></i>
      <span class="label label-success">4</span>
    </a>
</li>

<li>
    <a href="#">
      <i class="fa fa-bell-o"></i>
      <span class="label label-warning">7</span>
    </a>
</li>

<li>
    <a href="#">
      <i class="fa fa-flag-o"></i>
      <span class="label label-danger">9</span>
    </a>
</li>

HTML;
    }
}
```

次に、ナビゲーションバーに追加します：
```php
$navbar->right(new \App\Admin\Extensions\Nav\Links());
```

または、以下の HTML を使用してドロップダウンメニューを追加できます：
```html
<li class="dropdown notifications-menu">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-bell-o"></i>
  <span class="label label-warning">10</span>
</a>
<ul class="dropdown-menu">
  <li class="header">You have 10 notifications</li>
  <li>
    <!-- inner menu: contains the actual data -->
    <ul class="menu">
      <li>
        <a href="#">
          <i class="fa fa-users text-aqua"></i> 5 new members joined today
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
          page and may cause design problems
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-users text-red"></i> 5 new members joined
        </a>
      </li>

      <li>
        <a href="#">
          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-user text-red"></i> You changed your username
        </a>
      </li>
    </ul>
  </li>
  <li class="footer"><a href="#">View all</a></li>
</ul>
</li>
```

その他のコンポーネントは [Bootstrap](https://getbootstrap.com/) で見つけることができます。
