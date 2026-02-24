# カスタムチャート

`laravel-admin 1.5` ではすべてのチャートコンポーネントが削除されました。ページにチャートコンポーネントを追加したい場合は、以下の手順を参考にしてください。

`chartjs` を例として説明します。まず [chartjs](http://chartjs.org/) をダウンロードし、public ディレクトリ配下に配置します。例えば `public/vendor/chartjs` ディレクトリに置きます。

次に `app/Admin/bootstrap.php` でコンポーネントをインポートします：
```php
use Encore\Admin\Facades\Admin;

Admin::js('/vendor/chartjs/dist/Chart.min.js');

```

新しいビューファイル `resources/views/admin/charts/bar.blade.php` を作成します：

```php
<canvas id="myChart" width="400" height="400"></canvas>
<script>
$(function () {
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
});
</script>
```

これで、ページ内の任意の場所にこのチャートビューを導入できます：

```php
public function index()
{
    return Admin::content(function (Content $content) {

        $content->header('chart');
        $content->description('.....');

        $content->body(view('admin.charts.bar'));
    });
}

```

上記の方法で、任意のチャートライブラリを導入することができます。複数チャートのページレイアウトについては、[ビューレイアウト](/ja/layout.md) を参照してください。
