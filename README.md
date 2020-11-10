# backendkenshu2
## Issues
### （１）4-1の記事投稿サービスの用件に対する：
- [ ] 記事作成機能：tagselectorと同じにタグを自由に入力機能まだ実装しません。一回一つだけ入力できます。
- [ ] 記事編集機能：タグやイメージを編集機能まだ実装しません。
### 以下の同じ機能を開発しました。ほとんどフロントエンドを開発しましたので、上の部分（１）を一時停止しました：
- [x] 画像は複数枚アップロードすることができる
- [x] アップロードした画像のうち1枚をサムネイル画像として指定する
## CONFIG
 - myphpadmin port : 8080
 - http port : 9999
 - ルーティングすることはindex.phpに実現するので, 典型的なURLがこのような形があります：
   - index.php?(ROUTES)
   - index?controller=(CONTROLLER_NAME)&action=(ACTION_NAME)&id=(ITEM_ID)
   - 例 : localhost:9999/index.php?controller=articles&action=show&id=1
## DATABASE
![alt text](https://drive.google.com/uc?export=view&id=11LQ9hyYgSh-FIYc6FHMduZQAp6BunPUA)
### articles:
- id :
  - INT
  - PK*
  - 説明：記事のID
- title :
  - String
  - 説明：記事のタイトル
- content :
  - String
  - 説明：記事の内容
- created_at :
  - Datetime
  - 説明：作成時間
- modified_at :
  - Datetime
  - 説明：編集時間
- published :
  - tinyInt/Bool
  - 説明：リーリス状態
- author_id :
  - int
  - 説明：記事のオーナーID
  - FK*（参照：users)
### users:
- id :
  - INT
  - PK*
  - 説明：メンバーのID
- nickname :
  - String
  - 説明：メンバーのニックネーム
- email :
  - String
  - 説明：メンバーのemail
- pass :
  - hashed String
  - 説明：メンバーのパスワード
### images:
- id :
  - INT
  - PK*
  - 説明：イメージのID
- nickname :
  - String
  - 説明：メンバーのニックネーム
- email :
  - String
  - 説明：メンバーのemail
- article_id :
  - INT
  - 説明：イメージの記事のID
  - FK*（参照：articles)
### tags:
- id :
  - INT
  - PK*
  - 説明：タグのID
- name :
  - String
  - 説明：タグの名前
### article_tags:
- article_id :
  - INT
  - 説明：関係がある記事のID
- tag_id :
  - String
  - 説明：関係があるタグのID
## MODELS:
MVCのModelを実装してみます。
### Article:
DBのarticlesテーブルにマッピングします。
#### 
### User:
DBのusersテーブルにマッピングします
### Image:
DBのimagesテーブルにマッピングします
### Tag:
DBのtagsテーブルにマッピングします
## ROUTESとCONTROLLERS:
ルーティングすることはindex.phpに実現されます。MVC実装すると：Controllerごとにfolderというプロパティがあります。index.phpがこのプロパティから見るとパスなどを認識できます。
### Pages Routes :
スターティクページのルート
#### Home (GET) : ?controller=pages&action=home
#### Error (GET) : ?controller=pages&action=error
### Articles Route :
記事に関する処理のルート
#### Index (GET) : ?controller=articles&action=index
#### Show (GET) : ?controller=articles&action=show&id=(ID)
#### Add (GET) : ?controller=articles&action=add
#### Create (POST) : ?controller=articles&action=create
#### Edit (GET) : ?controller=articles&action=update&id=(ID)
#### Update (POST) : ?controller=articles&action=delete&id=(ID)
#### Delete (POST) : ?controller=articles&action=edit&id=(ID)
### Users Routes : 
ユーザーに関する処理のルート
#### Index (GET) : ?controller=users&action=index
#### Show (GET) : ?controller=users&action=show&id=(ID)
#### User's Articles (GET) : ?controller=users&action=articles
### Authentications Routes : 
ユーザーセッションなどに関する処理のルート
#### Login (GET) : ?controller=authentications&action=login
#### Signin (POST) : ?controller=authentications&action=signin
#### Register (GET) : ?controller=authentications&action=register
#### Check (GET) : ?controller=authentications&action=check
#### Signup (POST) : ?controller=authentications&action=signup
#### Thanks (GET) : ?controller=authentications&action=thanks
#### Logout (POST) : ?controller=authentications&action=logout
