# 最終課題：店舗情報配信APIの開発（Laravel × REST API × Postman）

商業施設のCMSが保有する店舗情報を、外部ベンダーへAPIで配信するバックエンドを開発する課題です。
REST APIの基本概念から、Laravelでの実装、JSON/XMLでのレスポンス生成、Postmanを使った動作検証までを一通り体験します。

> 📌 各課題の詳細は [Issues](../../issues) に登録されています。Issueを確認しながら進め、課題ごとにPull Requestを提出してください。

## 背景シナリオ

あなたは商業施設「サンライズモール」のWebサイト・CMSを運用する開発チームの一員です。
このたび、外部ベンダーが館内に設置するデジタルサイネージに「店舗一覧」や「本日の休業情報」を表示することになりました。

- サイネージの画面（フロントエンド）は**外部ベンダーが開発**します
- あなたの担当は、CMSが持つ店舗情報を**APIとして配信するバックエンド**です
- ベンダーによって希望するデータ形式が異なり、**JSON形式**と**XML形式**の両方に対応する必要があります

フロントエンドが手元にないため、開発中のAPIの動作確認には **Postman** を使用します。

## この課題で身につくこと

- REST APIの基本概念（リソース・HTTPメソッド・ステータスコード・エンドポイント設計）
- LaravelによるAPI実装（ルーティング・コントローラ・API Resource・バリデーション）
- JSON / XML 両形式でのレスポンス生成
- PostmanによるAPIの動作検証フロー

## 事前学習：REST APIとは

REST APIとは、「リソース（データ）」を「URL」で表し、「HTTPメソッド」で操作の種類を表現するAPI設計の考え方です。

| 操作 | HTTPメソッド | エンドポイント例 | 意味 |
|---|---|---|---|
| 一覧取得 | GET | `/api/v1/shops` | 店舗の一覧を取得する |
| 詳細取得 | GET | `/api/v1/shops/1` | ID=1の店舗を取得する |
| 新規登録 | POST | `/api/v1/shops` | 店舗を新規登録する |
| 更新 | PUT | `/api/v1/shops/1` | ID=1の店舗を更新する |
| 削除 | DELETE | `/api/v1/shops/1` | ID=1の店舗を削除する |

レスポンスには処理結果を表す**HTTPステータスコード**を添えます。最低限、以下を押さえてください。

- `200 OK`：成功（取得・更新）
- `201 Created`：作成成功
- `204 No Content`：成功したが返すデータがない（削除）
- `404 Not Found`：リソースが存在しない
- `422 Unprocessable Entity`：バリデーションエラー

より詳しい解説は以下を参照してください。

- [Laravel公式：Routing（API Routes）](https://laravel.com/docs/13.x/routing)
- [Laravel公式：Eloquent API Resources](https://laravel.com/docs/13.x/eloquent-resources)
- [Postman Learning Center](https://learning.postman.com/docs/introduction/overview/)（基本的な使い方は外部記事も適宜参照してください）

## 環境準備

1. このリポジトリをcloneする

   ```bash
   git clone git@github.com:Mahv3/laravel-api-kadai.git
   cd laravel-api-kadai
   ```

2. リポジトリ内に新規Laravelプロジェクトを作成する（Laravel 13 / PHP 8.3以上）

   ```bash
   composer create-project laravel/laravel sunrise-mall-api
   ```

3. API用ルーティングを有効化する（`routes/api.php` が生成されます）

   ```bash
   cd sunrise-mall-api
   php artisan install:api
   ```

   ※ `routes/api.php` に書いたルートには自動で `/api` プレフィックスが付きます。

4. 開発サーバーを起動し、ブラウザで `http://127.0.0.1:8000` にLaravelの初期画面が表示されることを確認する

   ```bash
   php artisan serve
   ```

   > ⚠️ `Address already in use` と表示された場合は、別のアプリがポート8000を使用しています。
   > `php artisan serve --port=8080` のように空いているポートを指定してください（以降のURLも読み替えてください）。

5. ここまでを「環境構築」としてブランチを切り、mainに向けて最初のPRを作成する

6. [Postman](https://www.postman.com/downloads/) をインストールし、アカウントを作成する

## データ仕様

店舗情報 `shops` テーブルを以下の仕様で作成してください。

| カラム | 型 | 制約 | 説明 |
|---|---|---|---|
| id | bigint | PK・自動採番 | 店舗ID |
| name | varchar(100) | 必須 | 店舗名 |
| floor | varchar(10) | 必須 | フロア（例：`1F`、`B1`） |
| category | varchar(50) | 必須 | カテゴリ（例：`レストラン`、`ファッション`、`雑貨`） |
| open_time | time | 必須 | 開店時刻 |
| close_time | time | 必須 | 閉店時刻 |
| tel | varchar(20) | NULL許可 | 電話番号 |
| description | varchar(500) | NULL許可 | 店舗紹介文 |
| is_temporarily_closed | boolean | デフォルト false | 臨時休業フラグ |
| created_at / updated_at | timestamp | | タイムスタンプ |

- マイグレーションでテーブルを作成すること
- FactoryとSeederを用意し、**15件以上**のダミー店舗データを投入すること（フロア・カテゴリはばらけさせる）

> 💡 `.env` に `APP_FAKER_LOCALE=ja_JP` を追加すると、Fakerが日本語のダミーデータ（会社名・文章など）を生成してくれます。

> 💡 **バリデーションを実装する際は、ここで定めたカラムの最大長と必ず一致させてください。**
> Trelloアプリのレビューで指摘した「DBスキーマとバリデーション最大値の不整合」を思い出しましょう。

## 課題一覧

| # | 課題 | 区分 |
|---|---|---|
| 1 | 店舗情報の参照API（JSON） | 必須 |
| 2 | 店舗情報の管理API（登録・更新・削除） | 必須 |
| 3 | XML形式での配信 | 必須 |
| 4 | APIキー認証・ページネーション・レートリミット | 任意 |

各課題の詳細要件・ヒント・Postman検証ケースは [Issues](../../issues) を参照してください。

## 提出方法

1. 課題ごとにブランチを切り、**すべてこのリポジトリのmainブランチに向けて**Pull Requestを作成してください（SQL課題のときと同じ運用です）
2. PRの説明欄に以下を含めてください
   - 実装内容の概要
   - Postmanでの検証結果のスクリーンショット（各検証ケース）
3. Postmanのコレクションをエクスポート（Collection → Export → v2.1）し、リポジトリの `postman/` ディレクトリに含めてください

## レビュー観点（評価ポイント）

- [ ] エンドポイントがRESTの設計原則（リソース名・HTTPメソッドの使い分け）に沿っているか
- [ ] 各処理で適切なHTTPステータスコードを返しているか
- [ ] バリデーションがDBスキーマと整合しているか
- [ ] API Resourceでレスポンスが整形されているか（モデルをそのまま返していないか）
- [ ] 存在しないリソース・不正な入力など、異常系がJSONで適切に処理されているか
- [ ] Postmanのコレクションが整理され、検証ケースが網羅されているか

<!-- 動作確認バージョン: Laravel 13.x（2026年3月17日リリース・PHP 8.3以上） / php artisan install:api でAPIルート有効化（2026-06-04時点の公式ドキュメントで確認） -->
