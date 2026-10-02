# Antennaize（アンテナイズ）

Livedoor・FC2・サーバー設置型に対応した、複数アンテナサイトを一括構築・管理するPHP製アンテナシステム。

## 基本方針
Antennaizeは共通バックエンドでRSS取得、記事管理、IN/OUT/PV、ランキング、相互RSS、固定ページ、広告、削除管理、cronを管理し、公開側だけをプラットフォーム別Adapterで切り替えます。

- Livedoor型: 中央管理 + AtomPub連携 + 管理画面内HTML/CSS保管・編集
- FC2型: 中央管理 + 外部連携Adapter + 管理画面内HTML/CSS保管・編集
- Server型: 中央管理 + サブドメイン別フロント + 複数テンプレート
- アンテナ間のRSS・記事・設定・ランキングは antenna_id で完全分離
- Search Console / Bing Webmaster Toolsへの登録は前提にしないが、Google/Bing向け通常SEOは実装
- W3C、セキュリティ、表示速度、レスポンシブを必須品質とする
- 定期処理はcronを使用

## 必要環境
PHP 8.1+ / MySQL 8.0+ または MariaDB 10.5+ / PDO MySQL / cURL / mbstring / SimpleXML / cron / HTTPS推奨

## ディレクトリ
```
admin/       管理画面
app/         共通コア
adapters/    Livedoor / FC2 / Server差分
api/         公開JSON API
config/      設定
cron/        定期処理
install/     初回インストーラー
public/      Server型フロント
resources/   テンプレート
storage/     キャッシュ・ログ
```

## 初期セットアップ
1. `config/config.example.php` を `config/config.php` にコピーしてDB情報を設定。
2. `/install/` を開き、テーブルと初期管理者を作成。
3. 管理画面は `/admin/login0929.php`。
4. アンテナを作成し、そのアンテナ内にRSSを登録。
5. Server型は対象ドメイン/サブドメインを同一DocumentRootへ向ける。
6. cronを登録。

## cron
```bash
php /path/to/Antennaize/cron/fetch.php
php /path/to/Antennaize/cron/rank.php
php /path/to/Antennaize/cron/cache.php
```

## 管理画面
ダッシュボード、アンテナ管理、RSS管理、記事管理、相互RSS、ランキング、IN/OUT/PV、HTML/CSS編集（Livedoor・FC2）、Server型テンプレート選択、広告、固定ページ、設定を共通管理します。

Livedoor/FC2のHTML・CSSはアンテナごとにDB保存し、Antennaizeを正本として編集します。各サービスAPIでテーマ自体を更新できない場合は、完成コードをコピーしてブログ管理画面へ反映します。

## Server型
Hostヘッダーから対象アンテナを解決します。各アンテナに `host`、`subdomain`、`template_key` を持たせ、同じコードから別サイトとして表示します。

初期テンプレートは standard / compact / dark。テンプレートは `resources/templates/{key}/` に分離してカスタマイズしやすくします。

## 公開API
```
GET /api/index.php?antenna=1&type=latest
GET /api/index.php?antenna=1&type=popular24
GET /api/index.php?antenna=1&type=popular7
GET /api/index.php?antenna=1&type=mutual
```
公開APIは読み取り専用で管理APIと分離します。

## SEO / Bing / W3C
Search Console / Bing Webmaster Toolsへの登録は必須にしませんが、title、meta description、canonical、robots.txt、sitemap.xml、OGP、JSON-LD、パンくず、404/410、重複URL抑制、セマンティックHTML、W3Cを意識したHTML/CSS、レスポンシブ、画像最適化、表示速度、Google/Bing双方がクロールしやすいURL・内部リンクを通常サイト同様に実装対象とします。

## セキュリティ
PDO prepared statements、XSS出力エスケープ、CSRF、password_hash/password_verify、session_regenerate_id、SameSite/HttpOnly/Secure Cookie、RSS取得SSRF対策、安全なXML解析、オープンリダイレクト防止、API CORS制御、管理画面認証、セキュリティヘッダー、cron多重実行防止を基本とします。アクセス解析やRSS更新失敗で公開画面を500にしません。

## 実装状況
初期コアとして、DBスキーマ、インストール、管理ログイン、アンテナ/RSS登録、RSS/Atom取得、重複防止、公開JSON API、IN/OUT/PV基礎、ランキングキャッシュ、Server型Hostルーティング、Livedoor/FC2フロントコード管理、複数Serverテンプレート、cron基盤を実装します。

残件・検証はGitHub Issuesを1件に集約します。
