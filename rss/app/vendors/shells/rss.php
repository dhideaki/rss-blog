<?php
/**
 * RSSシェル
 *
 * @author H.Asakura
 * @version $Id$
 */
class RssShell extends Shell
{
    // 使用するモデルクラス
    var $uses = array('Rss');

    /**
     * メイン処理
     *
     * @return void
     */
    function main()
    {
        // 2週間以上古いデータを削除
        $time = strftime("%Y-%m-%d %H:%M:%S", strtotime("-2 week"));
        $this->Rss->deleteAll(array('dc_date <' => $time));

        // 設定情報読み込み
        Configure::load('app_conf');
        $rssurl         = Configure::read('rssurl');        // FC2BLOGの新着情報RSS URL
        $url_pattern    = Configure::read('url_pattern');   // ユーザー名、サーバー番号、エントリーNo.抽出の正規表現
        $dc_url         = Configure::read('dc_url');        // RSS 1.0 の dc の名前空間

        // 新着情報RSSの取得
        $rssdata = simplexml_load_file($rssurl);
        if ($rssdata === false) {
            $this->log('simplexml 失敗 [' . $rssurl . ']', LOG_DEBUG);
            return;
        } else {

            // 更新チェック
            $rsshash = sha1(serialize($rssdata));   // シリアライズ、ハッシュ化
            $rsschck = Cache::read('rssdata');  // キャッシュから値を読み込み
            if ($rsshash === $rsschck) {        // 更新なし？
                $this->log('更新なし', LOG_DEBUG);
                return;
            }
            Cache::write('rssdata', $rsshash);  // キャッシュに値を保存

            // 新着情報RSSの保存
            foreach ($rssdata->item as $item) {
                // ユーザー名、サーバー番号、エントリーNo.取得
                $link = (string)$item->link;
                preg_match($url_pattern, $link, $match);
                $username = $server_no = $entry_no = null;
                if (!empty($match)) {
                    $username  = $match[1];
                    $server_no = intval($match[2]);
                    $entry_no  = intval($match[3]);
                }

                // RSS 1.0 の dc の名前空間
                $dc = $item->children($dc_url);

                // データ登録
                $data = array(
                    'title'        => (string)$item->title,
                    'link'         => (string)$item->link,
                    'description'  => (string)$item->description,
                    'dc_date'      => (string)$dc->date,
                    'username'     => $username,
                    'server_no'    => $server_no,
                    'entry_no'     => $entry_no,
                );
                $this->Rss->create($data);
                if ($this->Rss->save($data)) {
                    // 成功
                } else {
                    // 失敗
                    $this->log($data);
                }
            }
        }
    }
}
