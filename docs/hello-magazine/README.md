# Hello! Magazine ドキュメント

スライド「Hello! International School」内の **ハローマガジン（Hello! Magazine）** に関する
仕様の読み取り・設計・疑問点のまとめ。

- 出典スライド: https://docs.google.com/presentation/d/1RzRoZPF7j8WN81oAVs9OvPqOaKXEKvTm00isQUKetmk/edit
- 関連スライド（CMSタグ管理）: https://docs.google.com/presentation/d/1p-AO2dz7odihq-xuQY_pWq1bsV7NLdn2UjhtCuFyDys/edit
- 作成日: 2026-06-25

## 索引

| ファイル | 内容 |
|----------|------|
| [01-requirements.md](01-requirements.md) | スライドから読み取ったマガジン要件（ページ構成・各記事種別） |
| [02-data-model.md](02-data-model.md) | カスタム投稿・タクソノミー・ACFの設計（実装済みスキャフォルド） |
| [03-questions.md](03-questions.md) | **疑問点・要確認事項**（最重要。回答待ちでブロックされる項目を含む） |

## 重要な前提・注意

- 本ドキュメントはスライド**本文テキスト**を読み取って作成した。
  Google Slides の**コメント（吹き出しスレッド）は API 制約で取得できなかった**
  （`commentsNotSupported`）。スライド上に文章として書かれた「確認事項」「コメント風メモ」は
  本文として取得・反映済みだが、スレッド型コメントが別途ある場合は共有いただきたい
  （→ [03-questions.md](03-questions.md) Q0）。
- 実装は子テーマ `wp-content/themes/hello/` 配下に着手済み（CPT 5種・タクソノミー6種・
  ACFフィールドグループ5種）。詳細は [02-data-model.md](02-data-model.md)。
