# フォームフィールド管理

## フィールドの削除

組み込みの `map` および `editor` フィールドは CDN 経由でフロントエンドファイルを読み込みます。ネットワークに問題がある場合は、以下の方法で削除できます。

`app/Admin/bootstrap.php` ファイルを見つけてください。ファイルが存在しない場合は、`laravel-admin` を更新してこのファイルを作成してください。

```php

<?php

use Encore\Admin\Form;

Form::forget('map');
Form::forget('editor');

// または

Form::forget(['map', 'editor']);

```

これにより2つのフィールドが削除されます。この方法は他のフィールドの削除にも使用できます。

## カスタムフィールドの拡張

[codemirror](http://codemirror.net/index.html) ベースの PHP コードエディタを拡張する手順は以下の通りです。

[PHP mode](http://codemirror.net/mode/php/) を参照してください。

[codemirror](http://codemirror.net/codemirror.zip) ライブラリをダウンロードして解凍し、フロントエンドリソースディレクトリに配置します。例えば、`public/packages/codemirror-5.20.2` ディレクトリに配置します。

新しいフィールドクラス `app/Admin/Extensions/PHPEditor.php` を作成します:

```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class PHPEditor extends Field
{
    protected $view = 'admin.php-editor';

    protected static $css = [
        '/packages/codemirror-5.20.2/lib/codemirror.css',
    ];

    protected static $js = [
        '/packages/codemirror-5.20.2/lib/codemirror.js',
        '/packages/codemirror-5.20.2/addon/edit/matchbrackets.js',
        '/packages/codemirror-5.20.2/mode/htmlmixed/htmlmixed.js',
        '/packages/codemirror-5.20.2/mode/xml/xml.js',
        '/packages/codemirror-5.20.2/mode/javascript/javascript.js',
        '/packages/codemirror-5.20.2/mode/css/css.js',
        '/packages/codemirror-5.20.2/mode/clike/clike.js',
        '/packages/codemirror-5.20.2/mode/php/php.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    mode: "text/x-php",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("    " , "end");
        }
     }
});

EOT;
        return parent::render();

    }
}

```

>クラス内の静的リソースは外部からインポートすることもできます。[Editor.php](https://github.com/z-song/laravel-admin/blob/1.3/src/Form/Field/Editor.php) を参照してください。

ビューファイル `resources/views/admin/php-editor.blade.php` を作成します:

```php

<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('admin::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
    </div>
</div>

```

最後に、`app/Admin/bootstrap.php` ファイルを見つけます。ファイルが存在しない場合は、`laravel-admin` を更新してこのファイルを作成し、以下のコードを追加します:

```
<?php

use App\Admin\Extensions\PHPEditor;
use Encore\Admin\Form;

Form::extend('php', PHPEditor::class);

```

これで [model-form](/ja/model-form.md) で PHP エディタを使用できるようになります:

```
$form->php('code');

```

この方法で、必要な任意のフォームフィールドを追加できます。

## CKEditor の統合

CKEditor を統合する方法を示す別の例を紹介します。

まず [CKEditor](http://ckeditor.com/download) をダウンロードし、public ディレクトリに解凍します。例えば `public/packages/ckeditor/` に配置します。

次に、拡張クラス `app/Admin/Extensions/Form/CKEditor.php` を作成します:
```php
<?php

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/packages/ckeditor/ckeditor.js',
        '/packages/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'admin.ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClass()}').ckeditor();";

        return parent::render();
    }
}
```
ビュー `admin.ckeditor` 用のブレードファイル `resources/views/admin/ckeditor.blade.php` を追加します:
```php
<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>

```
`app/Admin/bootstrap.php` でこの拡張を登録します:

```php
use Encore\Admin\Form;
use App\Admin\Extensions\Form\CKEditor;

Form::extend('ckeditor', CKEditor::class);
```
これでフォーム内で CKEditor を使用できるようになります:

```php
$form->ckeditor('content');
```
