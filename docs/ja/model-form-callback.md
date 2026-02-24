# フォームコールバック

`model-form` には現在、コールバック関数を受け取る3つのメソッドがあります:

```php
// フォーム送信後のコールバック
$form->submitted(function (Form $form) {
    //...
});

// 保存前のコールバック
$form->saving(function (Form $form) {
    //...
});

// 保存後のコールバック
$form->saved(function (Form $form) {
    //...
});

```
必要に応じて、submitted 関数を使用して無視するフィールドを追加できます:
```php
$form->submitted(function (Form $form) {
    $form->ignore('username');

});

```
コールバックパラメータ `$form` から、現在送信されたフォームデータを取得できます:

```php
$form->saving(function (Form $form) {

    dump($form->username);

});

```

モデルのデータを取得する:
```php
$form->saved(function (Form $form) {

    $form->model()->id;

});
```

コールバック内で `Symfony\Component\HttpFoundation\Response` のインスタンスを直接返すことで、他のURLにリダイレクトできます:

```php
$form->saving(function (Form $form) {

    // シンプルなレスポンスを返す
    return response('xxxx');

});

$form->saving(function (Form $form) {

    // URLにリダイレクト
    return redirect('/admin/users');

});

$form->saving(function (Form $form) {

    // 例外をスロー
    throw new \Exception('Error friends. . .');

});

```

ページにエラーまたは成功メッセージを返す:

```php
use Illuminate\Support\MessageBag;

// エラーメッセージと共にリダイレクトバック
$form->saving(function ($form) {

    $error = new MessageBag([
        'title'   => 'title...',
        'message' => 'message....',
    ]);

    return back()->with(compact('error'));
});

// 成功メッセージと共にリダイレクトバック
$form->saving(function ($form) {

    $success = new MessageBag([
        'title'   => 'title...',
        'message' => 'message....',
    ]);

    return back()->with(compact('success'));
});

```
