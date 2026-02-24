# データエクスポート

`model-grid` の組み込みエクスポート機能は、シンプルな CSV 形式のファイルエクスポートを実現しています。ファイルのエンコーディング問題が発生したり、独自の要件を満たせない場合は、以下の手順に従ってエクスポート機能をカスタマイズできます。

この例では Excel ライブラリとして [Laravel-Excel](https://github.com/Maatwebsite/Laravel-Excel) を使用しますが、他の任意の Excel ライブラリを使用することもできます。

まず、インストールします:

```shell
composer require maatwebsite/excel:~2.1.0

php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

次に、新しいカスタムエクスポートクラスを作成します。例えば `app/Admin/Extensions/ExcelExpoter.php`:
```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // このロジックはテーブルデータからエクスポートが必要なカラムを取得します
                $rows = collect($this->getData())->map(function ($item) {
                    return Arr::only($item, ['id', 'title', 'content', 'rate', 'keywords']);
                });

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
```

そして、`model-grid` でこのクラスを使用します:
```php

use App\Admin\Extensions\ExcelExpoter;

$grid->exporter(new ExcelExpoter());

```

`Laravel-Excel` の使い方の詳細については、[laravel-excel/docs](http://www.maatwebsite.nl/laravel-excel/docs) を参照してください。
