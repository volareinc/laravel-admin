# フォームフィールド一覧

`model-form` には、フォームを素早く構築するための多数のフォームコンポーネントが組み込まれています。

## パブリックメソッド

### 保存する値を設定
```php
$form->text('title')->value('text...');
```

### デフォルト値を設定
```php
$form->text('title')->default('text...');
```

### ヘルプメッセージを設定
```php
$form->text('title')->help('help...');
```

### fa-icon クラスを設定
```php
$form->text('title')->icon('fa-copy');
```

### フィールド要素の属性を設定
```php
$form->text('title')->attribute(['data-title' => 'title...']);

$form->text('title')->attribute('data-title', 'title...');
```

### プレースホルダーを設定
```php
$form->text('title')->placeholder('Please input...');
```

### Model-form-tab

フォームに含まれるフィールドが多すぎると、フォームページが長くなりすぎます。その場合、タブを使用してフォームを分割できます:

```php

$form->tab('Basic info', function ($form) {

    $form->text('username');
    $form->email('email');

})->tab('Profile', function ($form) {

   $form->image('avatar');
   $form->text('address');
   $form->mobile('phone');

})->tab('Jobs', function ($form) {

     $form->hasMany('jobs', function () {
         $form->text('company');
         $form->date('start_date');
         $form->date('end_date');
     });

  })

```

## テキスト入力

```php
$form->text($column, [$label]);

// 送信時のバリデーションルールを追加
$form->text($column, [$label])->rules('required|min:10');
```

## セレクト (Select)
```php
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

オプションが多すぎる場合は、ajax でオプションを読み込むことができます:

```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');

// ajax を使用して選択済みの項目を表示:

$form->select('user_id')->options(User::class)->ajax('/admin/api/users');

// または name と id を指定

$form->select('user_id')->options(User::class, 'name', 'id')->ajax('/admin/api/users');
```

<sub>注意: `config/admin.php` の `route.prefix` の値を変更している場合、この API ルートは `config('admin.route.prefix').'/api/users'` に変更する必要があります。</sub>

API `/admin/api/users` のコントローラーメソッドは以下の通りです:

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```

API `/admin/demo/options` から返されるJSONは以下の形式です:
```
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```

### セレクトの連動 (Select linkage)

`select` コンポーネントは親子関係の一方向連動をサポートしています:
```php
$form->select('province')->options(...)->load('city', '/api/city');

$form->select('city');

```

`load('city', '/api/city');` は、現在のセレクトのオプションが変更された際に、選択された値を引数 `q` として API `/api/city` を呼び出し、返されたデータで city セレクトボックスのオプションを埋めることを意味します。API `/api/city` が返すデータの形式は以下と一致する必要があります:

```php
[
    {
        "id": 1,
        "text": "foo"
    },
    {
        "id": 2,
        "text": "bar"
    },
    ...
]
```
コントローラーアクションのコードは以下の通りです:

```php
public function city(Request $request)
{
    $provinceId = $request->get('q');

    return ChinaArea::city()->where('parent_id', $provinceId)->get(['id', DB::raw('name as text')]);
}
```

## 複数選択 (Multiple select)
```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);

// ajax を使用して選択済みの項目を表示:

$form->multipleSelect($column[, $label])->options(Model::class)->ajax('ajax_url');

// または name と id を指定

$form->multipleSelect($column[, $label])->options(Model::class, 'name', 'id')->ajax('ajax_url');
```

複数選択の値は2つの方法で保存できます。1つ目は `many-to-many` リレーションを使用する方法です。

```

class Post extends Models
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

$form->multipleSelect('tags')->options(Tag::all()->pluck('name', 'id'));

```

2つ目は、オプションの配列を単一のフィールドに格納する方法です。フィールドが文字列型の場合は、フィールドに対して [accessor と Mutator](https://laravel.com/docs/5.5/eloquent-mutators) を定義する必要があります。

オプションが多すぎる場合は、ajax でオプションを読み込むことができます。

```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```

<sub>注意: `config/admin.php` の `route.prefix` の値を変更している場合、この API ルートは `config('admin.route.prefix').'/api/users'` に変更する必要があります。</sub>

API `/admin/api/users` のコントローラーメソッドは以下の通りです:

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```

API `/admin/demo/options` から返されるJSONは以下の形式です:
```
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```

## リストボックス (Listbox)

使い方は multipleSelect と同じです。

```php
$form->listbox($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

## テキストエリア (Textarea)
```php
$form->textarea($column[, $label])->rows(10);
```

## ラジオボタン (Radio)
```php
$form->radio($column[, $label])->options(['m' => 'Female', 'f'=> 'Male'])->default('m');

$form->radio($column[, $label])->options(['m' => 'Female', 'f'=> 'Male'])->default('m')->stacked();
```

## チェックボックス (Checkbox)

`checkbox` は2つの方法で値を保存できます。[複数選択 (Multiple select)](#複数選択-multiple-select) を参照してください。

`options()` メソッドでオプションを設定します:
```php
$form->checkbox($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);

$form->checkbox($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name'])->stacked();
```

## メール入力 (Email input)
```php
$form->email($column[, $label]);
```

## パスワード入力 (Password input)
```php
$form->password($column[, $label]);
```

## URL入力 (URL input)
```php
$form->url($column[, $label]);
```

## IP入力 (Ip input)
```php
$form->ip($column[, $label]);
```

## 電話番号入力 (Phone number input)
```php
$form->mobile($column[, $label])->options(['mask' => '999 9999 9999']);
```

## カラー選択 (Color select)
```php
$form->color($column[, $label])->default('#ccc');
```

## 時間入力 (Time input)
```php
$form->time($column[, $label]);

// 時間フォーマットを設定。その他のフォーマットは http://momentjs.com/docs/#/displaying/format/ を参照
$form->time($column[, $label])->format('HH:mm:ss');
```

## 日付入力 (Date input)
```php
$form->date($column[, $label]);

// 日付フォーマットを設定。その他のフォーマットは http://momentjs.com/docs/#/displaying/format/ を参照
$form->date($column[, $label])->format('YYYY-MM-DD');
```

## 日時入力 (Datetime input)
```php
$form->datetime($column[, $label]);

// 日時フォーマットを設定。その他のフォーマットは http://momentjs.com/docs/#/displaying/format/ を参照
$form->datetime($column[, $label])->format('YYYY-MM-DD HH:mm:ss');
```

## 時間範囲選択 (Time range select)
`$startTime`、`$endTime` は開始時間と終了時間のフィールドです:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```

## 日付範囲選択 (Date range select)
`$startDate`、`$endDate` は開始日と終了日のフィールドです:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```

## 日時範囲選択 (Datetime range select)
`$startDateTime`、`$endDateTime` は開始日時と終了日時のフィールドです:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

## 通貨入力 (Currency input)
```php
$form->currency($column[, $label]);

// 通貨記号を設定
$form->currency($column[, $label])->symbol('￥');

```

## 数値入力 (Number input)
```php
$form->number($column[, $label]);
```

## レート入力 (Rate input)
```php
$form->rate($column[, $label]);
```

## 画像アップロード (Image upload)

アップロードフィールドを使用する前に、アップロードの設定を完了する必要があります。[ファイル/画像アップロード](/ja/model-form-upload.md) を参照してください。

圧縮、トリミング、ウォーターマークの追加などのメソッドを使用できます。詳しくは [[Intervention](http://image.intervention.io/getting_started/introduction)] を参照してください。画像アップロードディレクトリは `config/admin.php` の `upload.image` で設定します。ディレクトリが存在しない場合は、ディレクトリを作成して書き込み権限を付与してください:
```php
$form->image($column[, $label]);

// 画像のアップロードパスとファイル名を変更
$form->image($column[, $label])->move($dir, $name);

// 画像をトリミング
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// ウォーターマークを追加
$form->image($column[, $label])->insert($watermark, 'center');

// 削除ボタンを追加
$form->image($column[, $label])->removable();

```

## ファイルアップロード (File upload)

アップロードフィールドを使用する前に、アップロードの設定を完了する必要があります。[ファイル/画像アップロード](/ja/model-form-upload.md) を参照してください。

ファイルアップロードディレクトリは `config/admin.php` の `upload.file` で設定します。ディレクトリが存在しない場合は、作成して書き込み権限を付与してください。
```php
$form->file($column[, $label]);

// ファイルのアップロードパスとファイル名を変更
$form->file($column[, $label])->move($dir, $name);

// アップロードファイルのタイプを設定
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');

// 削除ボタンを追加
$form->file($column[, $label])->removable();

```

## 複数画像/ファイルアップロード (Multiple image/file upload)

```php
// 複数画像
$form->multipleImage($column[, $label]);

// 複数ファイル
$form->multipleFile($column[, $label]);

// 削除ボタンを追加
$form->multipleFile($column[, $label])->removable();
```

複数画像/ファイルフィールドから送信されるデータの型は配列です。MySQL テーブルのカラムの型が array であるか、MongoDB を使用している場合は、配列をそのまま保存できます。しかし、文字列型で配列データを保存する場合は、文字列フォーマットを指定する必要があります。例えば、JSON文字列で配列データを保存する場合は、モデルの Mutator でカラムの Mutator を定義する必要があります。`pictures` というフィールド名の場合、以下のように Mutator を定義します:

```php
public function setPicturesAttribute($pictures)
{
    if (is_array($pictures)) {
        $this->attributes['pictures'] = json_encode($pictures);
    }
}

public function getPicturesAttribute($pictures)
{
    return json_decode($pictures, true);
}
```
もちろん、他の任意のフォーマットを指定することもできます。

## 地図 (Map)

地図フィールドはネットワークリソースを参照します。ネットワークに問題がある場合は、[フォームフィールド管理](/ja/model-form-field-management.md) を参照してコンポーネントを削除してください。

緯度と経度の選択に使用します。`$latitude`、`$longitude` は緯度と経度のフィールドです。Laravel の `locale` が `zh_CN` に設定されている場合はTencent地図を使用し、それ以外の場合はGoogle マップを使用します:
```php
$form->map($latitude, $longitude, $label);

// Tencent地図を使用
$form->map($latitude, $longitude, $label)->useTencentMap();

// Googleマップを使用
$form->map($latitude, $longitude, $label)->useGoogleMap();
```

## スライダー (Slider)
年齢などの数値型フィールドの選択に使用できます:
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```
その他のオプションについては https://github.com/IonDen/ion.rangeSlider#settings を参照してください。

## リッチテキストエディタ (Rich text editor)

エディタフィールドはネットワークリソースを参照します。ネットワークに問題がある場合は、[フォームフィールド管理](/ja/model-form-field-management.md) を参照してコンポーネントを削除してください。

```php
$form->editor($column[, $label]);
```

## 隠しフィールド (Hidden field)
```php
$form->hidden($column);
```

## スイッチ (Switch)
スイッチの `On` と `Off` にはそれぞれ `1` と `0` の値が対応します:
```php
$states = [
    'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
    'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
];

$form->switch($column[, $label])->states($states);
```

## 表示フィールド (Display field)
フィールドの値を表示するだけで、操作は行いません:
```php
$form->display($column[, $label]);
```

## 区切り線 (Divide)
```php
$form->divide();
```

## HTML
HTMLを挿入します。引数には `Htmlable`、`Renderable` を実装したオブジェクト、または `__toString()` メソッドを持つオブジェクトを渡すことができます。
```php
$form->html('html contents');
```

## タグ (Tags)
カンマ (,) 区切りの文字列 `tags` を入力します。
```php
$form->tags('keywords');
```

## アイコン (Icon)
`font-awesome` アイコンを選択します。
```php
$form->icon('icon');
```

## HasMany

1対多のリレーションを扱うための組み込みテーブルです。以下は簡単な例です:

1対多のリレーションを持つ2つのテーブルがあります:

```sql
CREATE TABLE `demo_painters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `demo_paintings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `painter_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY painter_id (`painter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

テーブルのモデルは以下の通りです:
```php
<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painter extends Model
{
    public function paintings()
    {
        return $this->hasMany(Painting::class, 'painter_id');
    }
}

<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painting extends Model
{
    protected $fillable = ['title', 'body', 'completed_at'];

    public function painter()
    {
        return $this->belongsTo(Painter::class, 'painter_id');
    }
}
```

フォームのコードは以下のように構築します:
```php
$form->display('id', 'ID');

$form->text('username')->rules('required');
$form->textarea('bio')->rules('required');

$form->hasMany('paintings', function (Form\NestedForm $form) {
    $form->text('title');
    $form->image('body');
    $form->datetime('completed_at');
});

$form->display('created_at', 'Created At');
$form->display('updated_at', 'Updated At');
```

## Embeds

MySQL の `JSON` 型フィールドデータ、MongoDB の `object` 型データ、または MySQL の文字型カラムに `JSON` 文字列形式で複数フィールドのデータ値を格納する場合に使用します。

例えば、orders テーブルの `JSON` 型または文字列型の `extra` カラムに、複数フィールドのデータを格納する場合:

```php
class Order extends Model
{
    protected $casts = [
        'extra' => 'json',
    ];
}
```
フォーム内で以下のように使用します:
```php
$form->embeds('extra', function ($form) {

    $form->text('extra1')->rules('required');
    $form->email('extra2')->rules('required');
    $form->mobile('extra3');
    $form->datetime('extra4');

    $form->dateRange('extra5', 'extra6', 'Date range')->rules('required');

});

// タイトルをカスタマイズ
$form->embeds('extra', 'Extra', function ($form) {
    ...
});
```

コールバック関数内のフォーム要素の作成メソッドの呼び出しは、外部と同じです。
