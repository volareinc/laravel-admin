# json-editor（取り込み版）

このディレクトリは、laravel-admin の拡張 [`jxlwqq/json-editor`](https://github.com/laravel-admin-extensions/json-editor) の v1.0.8 を、そのまま取り込んだものである。フォームに JSON を編集する入力欄（`$form->json('列名')`）を足す。

## なぜ取り込んだのか

`jxlwqq/json-editor` は `composer.json` で `encore/laravel-admin ~1.6` に依存している。利用側が `encore/laravel-admin` をこの社内フォーク（`volareinc/laravel-admin`）へ切り替えると、この依存が解決できず、`jxlwqq/json-editor` を入れられなくなる。拡張の本体は PHP のクラス 3 つとビュー 1 つ、JavaScript のライブラリ（jsoneditor 6.2.1）だけなので、社内フォークに含めて一緒に配布する。

## 元のパッケージとの違い

利用側のコードと設定を変えずに済むよう、次のものは元のパッケージと同じにしてある。

| 項目 | 値 |
|---|---|
| PHP の名前空間 | `Jxlwqq\JsonEditor` |
| ビューの名前空間 | `laravel-admin-json-editor` |
| アセットを公開するタグ | `laravel-admin-json-editor`（公開先は `public/vendor/laravel-admin-ext/json-editor`） |
| 設定 | `config/admin.php` の `extensions.json-editor` |

社内フォークの `composer.json` は `replace` に `jxlwqq/json-editor` を書いている。このため、利用側で `jxlwqq/json-editor` を同時に入れることはできない。利用側の `composer.json` からは `jxlwqq/json-editor` を外す。

ライセンスは元のパッケージと同じ MIT で、[LICENSE](LICENSE) を同梱している。
