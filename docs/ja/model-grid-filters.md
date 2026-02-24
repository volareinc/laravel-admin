# データフィルター

`model-grid` はデータフィルターのセットを提供します:

```php
$grid->filter(function($filter){

    // デフォルトの id フィルターを削除
    $filter->disableIdFilter();

    // カラムフィルターを追加
    $filter->like('name', 'name');
    ...

});

```

## フィルタータイプ

現在サポートされているフィルタータイプは以下の通りです:

### Equal
`sql: ... WHERE `column` = ""$input""`：
```php
$filter->equal('column', $label);
```

### Not equal
`sql: ... WHERE `column` != ""$input""`：
```php
$filter->notEqual('column', $label);
```

### Like
`sql: ... WHERE `column` LIKE "%"$input"%"`：
```php
$filter->like('column', $label);
```

### Ilike
`sql: ... WHERE `column` ILIKE "%"$input"%"`：
```php
$filter->ilike('column', $label);
```

### Greater than
`sql: ... WHERE `column` > "$input"`：
```php
$filter->gt('column', $label);
```

### Less than
`sql: ... WHERE `column` < "$input"`：
```php
$filter->lt('column', $label);
```

### Between
`sql: ... WHERE `column` BETWEEN "$start" AND "$end"`：
```php
$filter->between('column', $label);

// datetime フィールドタイプを設定
$filter->between('column', $label)->datetime();

// time フィールドタイプを設定
$filter->between('column', $label)->time();
```

### In
`sql: ... WHERE `column` in (...$inputs)`：
```php
$filter->in('column', $label)->multipleSelect(['key' => 'value']);
```

### NotIn
`sql: ... WHERE `column` not in (...$inputs)`：
```php
$filter->notIn('column', $label)->multipleSelect(['key' => 'value']);
```

### Date
`sql: ... WHERE DATE(`column`) = "$input"`：
```php
$filter->date('column', $label);
```

### Day
`sql: ... WHERE DAY(`column`) = "$input"`：
```php
$filter->day('column', $label);
```

### Month
`sql: ... WHERE MONTH(`column`) = "$input"`：
```php
$filter->month('column', $label);
```

### Year
`sql: ... WHERE YEAR(`column`) = "$input"`：
```php
$filter->year('column', $label);
```

### Where

`where` を使用して、より複雑なクエリフィルタリングを構築できます。

`sql: ... WHERE `title` LIKE "%$input" OR `content` LIKE "%$input"`：
```php
$filter->where(function ($query) {

    $query->where('title', 'like', "%{$this->input}%")
        ->orWhere('content', 'like', "%{$this->input}%");

}, 'Text');
```

`sql: ... WHERE `rate` >= 6 AND `created_at` = {$input}`:
```php
$filter->where(function ($query) {

    $query->whereRaw("`rate` >= 6 AND `created_at` = {$this->input}");

}, 'Text');
```

リレーションクエリ、対応するリレーション `profile` のフィールドをクエリします:
```php
$filter->where(function ($query) {

    $query->whereHas('profile', function ($query) {
        $query->where('address', 'like', "%{$this->input}%")->orWhere('email', 'like', "%{$this->input}%");
    });

}, 'Address or mobile');
```

## フィールドタイプ

デフォルトのフィールドタイプはテキスト入力です。テキスト入力のプレースホルダーを設定するには:

```php
$filter->equal('column')->placeholder('Please input...');
```

以下のメソッドを使用して、ユーザーの入力形式を制限することもできます:

```php
$filter->equal('column')->url();

$filter->equal('column')->email();

$filter->equal('column')->integer();

$filter->equal('column')->ip();

$filter->equal('column')->mac();

$filter->equal('column')->mobile();

// $options は https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md を参照
$filter->equal('column')->decimal($options = []);

// $options は https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md を参照
$filter->equal('column')->currency($options = []);

// $options は https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md を参照
$filter->equal('column')->percentage($options = []);

// $options は https://github.com/RobinHerbots/Inputmask を参照
$filter->equal('column')->inputmask($options = [], $icon = 'pencil');
```

### Select
```php
$filter->equal('column')->select(['key' => 'value'...]);

// または API からデータを取得。API フォーマットは model-form の `select` コンポーネントを参照
$filter->equal('column')->select('api/users');
```

### multipleSelect
通常、配列のクエリが必要な `in` と `notIn` の2つのタイプのクエリと組み合わせて使用されます。`type` タイプのクエリでも使用できます:
```php
$filter->in('column')->multipleSelect(['key' => 'value'...]);

// または API からデータを取得。API フォーマットは model-form の `multipleSelect` コンポーネントを参照
$filter->in('column')->multipleSelect('api/users');
```

### radio
より一般的なシナリオはカテゴリの選択です:

```php
$filter->equal('released')->radio([
    ''   => 'All',
    0    => 'Unreleased',
    1    => 'Released',
]);
```

### checkbox
より一般的なシナリオは `whereIn` でスコープクエリを行うことです:

```php
$filter->in('gender')->checkbox([
    'm'    => 'Male',
    'f'    => 'Female',
]);
```

### datetime

日付と時刻のコンポーネントを使用します。`$options` パラメータと値は [bootstrap-datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/Options/) を参照してください。

```php
$filter->equal('column')->datetime($options);

// `date()` は `datetime(['format' => 'YYYY-MM-DD'])` と同等
$filter->equal('column')->date();

// `time()` は `datetime(['format' => 'HH:mm:ss'])` と同等
$filter->equal('column')->time();

// `day()` は `datetime(['format' => 'DD'])` と同等
$filter->equal('column')->day();

// `month()` は `datetime(['format' => 'MM'])` と同等
$filter->equal('column')->month();

// `year()` は `datetime(['format' => 'YYYY'])` と同等
$filter->equal('column')->year();

```

## 複雑なクエリフィルター

`$this->input` を使用して、複雑なカスタムクエリをトリガーできます:
```php
$filter->where(function ($query) {
    switch ($this->input) {
        case 'yes':
            // 'yes' オプションが選択された場合のカスタム複雑クエリ
            $query->has('somerelationship');
            break;
        case 'no':
            $query->doesntHave('somerelationship');
            break;
    }
}, 'Label of the field', 'name_for_url_shortcut')->radio([
    '' => 'All',
    'yes' => 'Only with relationship',
    'no' => 'Only without relationship',
]);
```
