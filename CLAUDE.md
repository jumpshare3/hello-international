# hello-international プロジェクト運用ルール

magazine.hello-internationalschool.com（WordPress / 親テーマ SWELL ＋ 子テーマ）の
開発リポジトリ。本ファイルはこのプロジェクト固有のルールで、ユーザーグローバル
（`~/.claude/CLAUDE.md`）と併用される。

---

## ブランチ戦略

| ブランチ | 役割 | デプロイ先 |
|----------|------|-----------|
| `main` | 本番リリース用 | **本番サーバー**（ConoHa: magazine.hello-internationalschool.com）へ rsync |
| `staging` | 統合・動作確認用 | **ローカル環境**（Docker）で確認 |
| `feature/*` | 個別課題の作業用 | （デプロイなし） |

### 作業フロー（基本）

1. 作業者は **`staging` から `feature/*` ブランチを切る**。
   ```bash
   git checkout staging && git pull
   git checkout -b feature/<課題内容>
   ```
2. 実装したら **commit & push**（feature ブランチを push）。
3. `feature/*` → `staging` に取り込み（PR/マージ）、**ローカル環境で確認**。
4. ユーザー or AI がレビューし **OK と判断**したら `staging` → `main` に反映。
5. `main` を **本番サーバーへ rsync デプロイ**（後述）。

> 原則として作業ブランチは `staging` 起点。`main` へ直接 push しない。

---

## 修正後の必須チェック（ワイヤー差分・社内QA）

**クライアントの確認回数を減らすため、社内チェックを強化する。**
修正・実装のたびに、**必ずワイヤーフレームと突き合わせて漏れを潰してから**
次（staging統合・レビュー・クライアント共有）へ進む。

- ワイヤー: https://x.gd/LIsc6 （= デッキ `1RzRoZPF7j8WN81oAVs9OvPqOaKXEKvTm00isQUKetmk`）
- チェックリスト: [docs/hello-magazine/07-wireframe-checklist.md](docs/hello-magazine/07-wireframe-checklist.md)

### done の条件（必須手順）
1. 触ったページ／コンポーネントに対応する**ワイヤーの該当ページを開く**。
2. チェックリストの**該当ページ項目を1つずつ突き合わせ**、表示・項目・遷移の漏れを確認する。
3. 漏れがあれば**その場で修正**。仕様不明・判断保留は「要確認」として記録し、**勝手に省略しない**。
4. ローカルで実画面を表示（必要に応じスクショ）し、**チェックリスト全項目を確認**してから完了とする。
5. 作業報告に**チェック結果（OK項目／残課題）を明記**する。

### ルール
- 「動いた」だけで完了にしない。**ワイヤー項目を満たして初めて完了**。
- 新しいページ種別を作ったら、チェックリストに**その種別の項目を追記してから**着手する。
- ワイヤーが更新されたら、チェックリストも更新する（差分の発生源を一本化）。
- 過去に漏れた箇所（例：エージェント詳細の得意分野/拠点情報）は特に厚く確認する。

---

## Issue 運用（GitHub）

- 対応は **GitHub Issue を起点**にする。タスクが発生したら **AI が Issue を起票**する
  （`gh issue create`／リポジトリ `jumpshare3/hello-international`）。
- Issue には対応内容・対象画面・完了条件を記載。
- 対応の commit メッセージ／PR に Issue 番号を紐付ける（`#<番号>` / `Closes #<番号>`）。

---

## 完了報告（Chatwork）

課題対応が **commit & push まで完了**したら、下記 Chatwork ルームに完了報告を投稿する。
（これは判断を伴うため Claude が実施。フックではない。）

- **投稿先ルーム**: `436332578`
- **メンション**（本文先頭に必ず全員分を付与）:
  ```
  [To:1409289]沼部　浩一さん
  [To:941338]都野 幸子さん
  [To:3563100]猪口（桑澤）朝子さん
  [To:11367636]田中貴士さん
  ```
- **本文に含める**: 対応した Issue 番号・概要・commit/PR の URL・確認方法（ローカル URL 等）。
- **投稿コマンド**:
  ```bash
  python3 ~/.claude/chatwork-tools/post_chatwork_message.py 436332578 "本文" --dry-run  # 確認
  python3 ~/.claude/chatwork-tools/post_chatwork_message.py 436332578 "本文"            # 投稿
  ```
  - 本文は `[To:...]` メンション → 報告内容、の順。`--title "件名"` で件名付与可。
  - `--dry-run` で内容を確認してから本投稿する。

### 画面キャプチャの同時投稿

- 文脈から判断できる場合は、**対象画面のキャプチャも同時に投稿**する
  （ローカル http://localhost:8080 のレンダリング結果など）。
- ⚠️ 現状の `post_chatwork_message.py` は**テキスト投稿のみ**で画像添付に未対応。
  画像を添付する場合は Chatwork のファイルアップロード API
  （`POST /rooms/{room_id}/files`）が必要。初めて添付が必要になった時点で
  ツールを拡張する（または手動添付）。それまではキャプチャ画像のパスを
  本文で示す等で代替し、対応漏れにしない。

---

## デプロイ（rsync）

**レビューで OK と判断されたものだけ**をサーバーへ反映する。実行前に必ず
`--dry-run` で差分を確認し、ユーザーの明示承認を得てから本実行する。

### 子テーマのディレクトリ名

- ローカル/リポジトリ・本番サーバーともに `wp-content/themes/hello/`
  （DB: `stylesheet=hello` / `template=swell`）。
- 2026-06-25 に本番の旧 `swell_child` を `hello` へ統一済み（ディレクトリ rename ＋ `switch_theme('hello')`）。

### main → 本番（production）

```bash
# まず差分確認（--dry-run）
rsync -avzn --delete -e "ssh -p 8022" \
  ./wp-content/themes/hello/ \
  c9969246@www1188.conoha.ne.jp:public_html/magazine.hello-internationalschool.com/wp-content/themes/hello/

# 承認後、-n を外して本実行
```

- `--delete` は本番ファイル削除を伴うため特に慎重に。`--dry-run` 必須。
- 親テーマ SWELL（有償）は通常デプロイ対象外（本番に既存）。

### staging → ローカル

- ローカルは Docker のバインドマウント（`./wp-content/themes/hello` → コンテナ）。
  `staging` を checkout して `docker compose up -d` で即反映されるため rsync 不要。

---

## ローカル環境

```bash
docker compose up -d        # http://localhost:8080（WP） / http://localhost:8081（phpMyAdmin）
```

詳細は [README.md](README.md) を参照。
