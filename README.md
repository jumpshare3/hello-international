# hello-international

magazine.hello-internationalschool.com の WordPress テーマ開発リポジトリ。

本番サイトは ConoHa WING（www1188.conoha.ne.jp）上の WordPress で、
親テーマ **SWELL**＋子テーマ **swell_child** で構成されている。
このリポジトリでは開発対象である **子テーマ swell_child** と
**ローカル開発環境（Docker）** をバージョン管理する。

## 構成

```
hello-international/
├── docker-compose.yml          # ローカル WordPress 一式（WP + MySQL + phpMyAdmin）
├── .gitignore
├── README.md
└── wp-content/
    └── themes/
        ├── swell/        # SWELL 本体（有償テーマ。.gitignore で除外・Git管理外）
        └── swell_child/  # ★開発対象（Git管理）
```

> SWELL 本体は有償テーマのためコミットしない。`.gitignore` で除外している。
> 別マシンで開発を始める際は、ライセンス購入済みの SWELL を
> `wp-content/themes/swell/` に配置すること（本番サーバーからの取得も可）。

## ローカル環境の起動

前提: Docker / Docker Compose

```bash
docker compose up -d
```

| URL | 用途 |
|-----|------|
| http://localhost:8080 | WordPress サイト／管理画面（初回はセットアップウィザード） |
| http://localhost:8081 | phpMyAdmin（DB確認用） |

初回アクセスで WordPress のインストール画面が出るので、サイト名・管理ユーザーを設定し、
管理画面の「外観 > テーマ」で **SWELL CHILD** を有効化する。

### 停止 / 破棄

```bash
docker compose stop          # 停止（データ保持）
docker compose down          # コンテナ削除（データボリュームは保持）
docker compose down -v       # DB・WPコアごと完全初期化
```

## 本番テーマとの同期（参考）

本番サーバーからテーマを再取得する場合:

```bash
ssh -p 8022 c9969246@www1188.conoha.ne.jp \
  'cd ~/public_html/magazine.hello-internationalschool.com/wp-content/themes && tar czf - swell swell_child' \
  | tar xzf - -C wp-content/themes
```
