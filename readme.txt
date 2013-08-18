■概要
Web、バッチともCakePHPを使用しています。
コーディング規約については、PEARのコーディング標準に近い形で実装しています。
http://pear.php.net/manual/ja/standards.php
CakePHPで推薦されている方法と重なる箇所はCakePHPの方法を優先しています。


■動作環境
言語：PHP5
フレームワーク：CakePHP 1.3.8


■導入手順
●公開ディレクトリに設置

●パーミッションの変更
app/tmp 以下全部のディレクトリに対して、所有者以外でも書き込めるように変更する。
例
chmod -R 777 app/tmp

●各.htaccessファイルの設定
RewriteBaseを環境に合わせて変更する

・.htaccess
<IfModule mod_rewrite.c>
   RewriteEngine on
   RewriteRule    ^$ app/webroot/    [L]
   RewriteRule    (.*) app/webroot/$1 [L]
   RewriteBase    /~kd167/rss           ←変更
</IfModule>

・app/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule    ^$    webroot/    [L]
    RewriteRule    (.*) webroot/$1    [L]
    RewriteBase    /~kd167/rss/app      ←変更
</IfModule>

・app/webroot/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
    RewriteBase    /~kd167/rss/app/webroot  ←変更
</IfModule>

●DB設定
DB設定ファイルを環境に合わせて変更する
・app/config/database.php

●テーブル作成
rssesテーブルを作成する
添付ファイル[db.sql]のsql文を実行する

●crontab 設定
5分毎に起動するCRONを設定する。
appディレクトリに移動し、'cake/console/cake rss'を実行するコマンドを登録。
例
0,5,10,15,20,25,30,35,40,45,50,55 * * * * cd /home/kd167/public_html/rss/app && /home/kd167/public_html/rss/cake/console/cake rss >/dev/null 2>&1


■開発ファイル
今回の開発対象のファイルは以下の通りです。
その他はCakePHPフレームワークのファイルとなります。

app/config
    app_conf.php            アプリケーション設定
    core.php                CakePHP基本設定
    database.php            DB設定
    db.sql                  テーブル作成SQL
app/controllers
    app_controller.php      アプリケーション基本コントローラークラス
    rsses_controller.php    RSSコントローラークラス
app/vendors/shells
    rss.php                 RSS読み込み登録バッチ
app/view/elements
    pageing.ctp             ページング表示テンプレート
app/view/layouts
    default.ctp             レイアウトテンプレート
app/view/rsses
    index.ctp               RSSビューテンプレート
