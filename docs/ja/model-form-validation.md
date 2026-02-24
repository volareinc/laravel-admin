# フォームバリデーション

`model-form` は Laravel のバリデーションルールを使用して、フォームから送信されたデータを検証します:

```php
$form->text('title')->rules('required|min:3');

// コールバックで複雑なバリデーションルールを実装できます
$form->text('title')->rules(function ($form) {

    // 編集状態でない場合、フィールドのユニークバリデーションを追加
    if (!$id = $form->model()->id) {
        return 'unique:users,email_address';
    }

});

```

バリデーションルールのエラーメッセージをカスタマイズすることもできます:

```php
$form->text('code')->rules('required|regex:/^\d+$/|min:10', [
    'regex' => 'code must be numbers',
    'min'   => 'code can not be less than 10 characters',
]);
```

フィールドを空値許可にする場合は、まずデータベーステーブルでそのフィールドを `NULL` に設定し、次に以下のようにします:

```php
$form->text('title')->rules('nullable');
```

その他のルールについては [Validation](https://laravel.com/docs/5.5/validation) を参照してください。
