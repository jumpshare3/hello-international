# 02. データモデル（実装済みスキャフォルド）

子テーマ `wp-content/themes/hello/` 配下に、カスタム投稿・タクソノミー・ACFを
モジュール分割で実装済み。管理画面では親メニュー **「Hello! Magazine」** 配下に各CPTを集約。

## ディレクトリ構成

```
wp-content/themes/hello/
├── functions.php            # inc/ を順番に読み込むローダー＋子テーマCSS
├── acf-json/                # ACFフィールドグループのJSON同期先（GUI編集を版管理）
└── inc/
    ├── setup.php            # ACF acf-json 連携、ライセンス方針
    ├── admin-menu.php       # 親メニュー「Hello! Magazine」
    ├── taxonomies/
    │   ├── content.php      # hello_tag / hello_persona / hello_ranking_type
    │   └── attributes.php   # hello_curriculum / hello_region / hello_price
    ├── post-types/
    │   ├── live.php         # hello_live      … YouTube LIVE
    │   ├── interview.php    # hello_interview … インタビュー
    │   ├── faq.php          # hello_faq       … よくある質問
    │   ├── ranking.php      # hello_ranking   … ランキング（一覧＋詳細）
    │   └── agent.php        # hello_agent     … エージェント比較（一覧＋詳細）
    └── acf/
        ├── fields-live.php / fields-interview.php / fields-faq.php
        └── fields-ranking.php / fields-agent.php
```

## カスタム投稿タイプ（CPT）

| slug | 名称 | アーカイブ | 主なACF |
|------|------|:--:|------|
| `hello_live` | YouTube LIVE | ○ | youtube_url, live_date, members_only, chapters[], qa[] |
| `hello_interview` | インタビュー | ○ | school_name, position, qa[]（category/q/a） |
| `hello_faq` | よくある質問 | ○ | faq_audience, sections[]（section_label, items[]） |
| `hello_ranking` | ランキング | ○ | 比較条件, entries[]（順位/評価4+総合/費用/写真 …） |
| `hello_agent` | エージェント比較 | ○ | タイプタグ, support_fields[]（★合計20）, 体制タグ, 実績 … |

- URL: `/magazine/live/...` のように `magazine/<種別>` を rewrite slug に設定。

## タクソノミー

| slug | 用途 | 付与先 |
|------|------|------|
| `hello_tag` | 横断タグ（CMSタグ管理と同体系にそろえる） | 全CPT |
| `hello_persona` | 話者・立場（卒ママ/現ママ/卒生徒/在校生） | live / interview / faq |
| `hello_ranking_type` | ランキングの切り口 | ranking |
| `hello_curriculum` | カリキュラム（IB/British/American 等） | agent（将来 school） |
| `hello_region` | エリア（KL中心部/モントキアラ 等） | agent（将来 school） |
| `hello_price` | 価格帯（RM20,000以下 等） | agent（将来 school） |

## 導入プラグイン

| プラグイン | バージョン | 用途 | Git管理 |
|-----------|-----------|------|:--:|
| Advanced Custom Fields PRO | 6.8.4 | 構造化フィールド（繰り返し/画像/真偽 等） | 除外（有償・.gitignore） |

- ACF Pro ライセンス（developer / order 106825）は `.env` の `ACF_PRO_LICENSE` から
  `WORDPRESS_CONFIG_EXTRA` 経由で `define('ACF_PRO_LICENSE', ...)` として注入。
  初回 wp-admin ログイン時に自動アクティベートされる。Pro機能は有効化状態に関わらず動作。

## 設計上の判断（要確認は 03-questions.md）

- **タグ vs カテゴリ**: 記事種別はCPTで分離し、横断タグ `hello_tag` で串刺し。
  TOPの「タグ→種別表示」はCPT＋タグの組合せで実現可能。
- **ランキング/エージェントのエントリ**: ACF繰り返しで**手動エントリ**可能な設計。
  「学校カード自動挿入」は学校データ基盤に依存（未確定）。
- **CPTはテーマに同梱**（ご指示どおり）。本来はテーマ非依存の観点でプラグイン化が定石
  → 03-questions.md Q9 参照。
