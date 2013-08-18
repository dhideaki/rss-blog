<?php
/**
 * アプリケーション設定情報
 *
 * CakePHPのConfigureクラス使用
 *
 * @author H.Asakura
 * @version $Id$
 */
$config = array(
    // １ページの表示件数
    'page_limit' => 20,

    // FC2BLOGの新着情報RSS URL
    'rssurl' => 'http://blog.fc2.com/newentry.rdf',

    // ユーザー名、サーバー番号、エントリーNo.抽出の正規表現
    'url_pattern' => '/^http:\/\/([^\.]*)\.blog([^\.]*)\.fc2\.com\/blog-entry-([^\.]*)\.html/',

    // RSS 1.0 の dc の名前空間
    'dc_url' => 'http://purl.org/dc/elements/1.1/',
);
