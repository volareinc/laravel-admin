# カスタムツール

`model-grid` にはデフォルトで `一括削除` と `リフレッシュ` の操作ツールがあります。より多くの操作が必要な場合、`model-grid` はカスタムツール機能を提供します。以下の例では、`性別セレクター` ボタングループツールの追加方法を示します。

まず、ツールクラス `app/Admin/Extensions/Tools/UserGender.php` を定義します:

```php
<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class UserGender extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['gender' => '_gender_']);

        return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_gender_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => 'All',
            'm'     => 'Male',
            'f'     => 'Female',
        ];

        return view('admin.tools.gender', compact('options'));
    }
}

```
ビュー `admin.tools.gender` の blade ファイルは `resources/views/admin/tools/gender.blade.php` です:
```php
<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-default btn-sm {{ \Request::get('gender', 'all') == $option ? 'active' : '' }}">
        <input type="radio" class="user-gender" value="{{ $option }}">{{$label}}
    </label>
    @endforeach
</div>
```

`model-grid` でこのツールをインポートします:
```php

$grid->tools(function ($tools) {
    $tools->append(new UserGender());
});

```

`model-grid` 内で、`gender` クエリをモデルに渡します:
```php
if (in_array(Request::get('gender'), ['m', 'f'])) {
    $grid->model()->where('gender', Request::get('gender'));
}
```

上記の方法を参考にして、独自のツールを追加できます。

## 一括操作

現在、デフォルトで一括削除操作が実装されています。一括削除操作を無効にしたい場合は:
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->disableDelete();
    });
});

```

カスタムの一括操作を追加したい場合は、以下の例を参考にしてください。

以下の例では、`投稿の一括公開` 操作の実装方法を示します:

まず、ツールクラス `app/Admin/Extensions/Tools/ReleasePost.php` を定義します:
```php
<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class ReleasePost extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '{$this->resource}/release',
        data: {
            _token:LA.token,
            ids: selectedRows(),
            action: {$this->action}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});

EOT;

    }
}
```

上記のコードを見てください。ajax を使用して、選択された `ids` を POST リクエストでバックエンド API に渡します。バックエンド API は受信した `ids` に基づいて対応するデータの状態を変更し、フロントエンドはページをリフレッシュ（pjax reload）して、`toastr` で操作成功のプロンプトを表示します。

`model-grid` でこの操作をインポートします:
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->add('Release post', new ReleasePost(1));
        $batch->add('Unrelease post', new ReleasePost(0));
    });
});
```

これにより、一括操作のドロップダウンボタンに上記の2つの操作が追加されます。最後のステップは、一括操作のリクエストを処理する API を追加することです。API のコードは以下の通りです:
```php

class PostController extends Controller
{
    ...

    public function release(Request $request)
    {
        foreach (Post::find($request->get('ids')) as $post) {
            $post->released = $request->get('action');
            $post->save();
        }
    }

    ...
}
```

次に、上記の API のルートを追加します:
```php
$router->post('posts/release', 'PostController@release');
```

これで全体のプロセスが完了です。
