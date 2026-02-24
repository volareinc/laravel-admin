# ツリー構造 (Model Tree)

`model-tree` を使用すると、ツリー型のコンポーネントを実現できます。ドラッグ操作によって階層データの並び替えなどの操作が可能です。以下は基本的な使い方です。

## テーブル構造とモデル

`model-tree` を使用するには、以下のテーブル構造の規約に従う必要があります：

```sql
CREATE TABLE `demo_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```
上記のテーブル構造には、`parent_id`、`order`、`title` の3つの必須フィールドがあります。その他のフィールドは任意です。

対応するモデルは `app/Models/Category.php` です：
```php
<?php

namespace App\Models\Demo;

use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelTree;

    protected $table = 'demo_categories';
}
```

テーブル構造の3つのフィールド `parent_id`、`order`、`title` のカラム名は変更することができます：

```php
<?php

namespace App\Models\Demo;

use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelTree;

    protected $table = 'demo_categories';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('pid');
        $this->setOrderColumn('sort');
        $this->setTitleColumn('name');
    }
}
```
## 使い方

次に、ページ内で `model-tree` を使用します：

```php
<?php

namespace App\Admin\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class CategoryController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Categories');
            $content->body(Category::tree());
        });
    }
}
```
以下の方法でブランチの表示をカスタマイズできます：
```php
Category::tree(function ($tree) {
    $tree->branch(function ($branch) {
        $src = config('admin.upload.host') . '/' . $branch['logo'] ;
        $logo = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";

        return "{$branch['id']} - {$branch['title']} $logo";
    });
})
```

`$branch` パラメータは、現在の行データの配列です。

モデルのクエリを変更したい場合は、以下の方法を使用してください：
```php

Category::tree(function ($tree) {

    $tree->query(function ($model) {
        return $model->where('type', 1);
    });

})
```
