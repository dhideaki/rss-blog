<?php
/**
 * RSSコントローラー
 *
 * @author H.Asakura
 * @version $Id$
 */
class RssesController extends AppController
{
    // コントローラー名
    var $name = "Rsses";

    // 使用するモデルクラス
    var $uses = array('Rss');

    // Pagination情報の設定
    var $paginate = array(
        'Rss' => array(
            'limit'	=> 20,
            'order' => array('dc_date' => 'desc'),
            'recursive' => -1,
        ),
    );

    /**
     * beforeFilter処理
     *
     * コントローラーアクションの前に呼ばれます
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        // CSRF対策 トークンチェック
        $this->Security->requireAuth('index');

        // クッキーコンポーネントの設定
        $this->Cookie->name = 'MyCookie';

        // 設定情報読み込み
        Configure::load('app_conf');
        $limit = Configure::read('page_limit'); // 1ページの表示件数
        if (!empty($limit)) {
            $this->paginate['Rss']['limit'] = intval($limit);
        }
    }

    /**
     * インデックスアクション
     *
     * 検索／一覧処理
     *
     * @return void
     */
    function index()
    {
        // COOKIE
        if (!empty($this->data)) {
            $this->Cookie->write('searchQuery', serialize($this->data));
        } else {
            $data = $this->Cookie->read('searchQuery');
            if (!empty($data)) {
                $this->data = unserialize($data);
            }
        }

        // 検索条件
        $conds = array();
        if (!empty($this->data)) {
            // 日付（期間検索）
            $keys = array('start', 'end');
            $rets = $this->searchDate($this->data['Rss'], $keys, 'Rss', 'dc_date');
            if (!empty($rets['start'])) {
                $conds['Rss.dc_date >='] = $rets['start'];
            }
            if (!empty($rets['end'])) {
                $conds['Rss.dc_date <='] = $rets['end'];
            }

            // URL
            $val = $this->getDataParam('Rss', 'link');
            if (strlen($val) > 0) {
                $conds['Rss.link LIKE'] = '%' . $val . '%';
            }

            // ユーザー名
            $val = $this->getDataParam('Rss', 'username');
            if (strlen($val) > 0) {
                $conds['Rss.username'] = $val;
            }

            // サーバー番号
            $val = $this->getDataParam('Rss', 'server_no');
            $val = intval($val);
            if ($val == 0) {
                $val = '';
            }
            if (strlen($val) > 0) {
                $conds['Rss.server_no'] = $val;
            }
            $this->data['Rss']['server_no'] = $val;

            // エントリーNo.以上を検索対象 チャックボックス
            $check = 0;
            $val = $this->getDataParam('Rss', 'entry_no_flg');
            if (strlen($val) > 0) {
                if ($val === '1') {
                    $check = 1;
                }
            }

            // エントリーNo.
            $val = $this->getDataParam('Rss', 'entry_no');
            $val = intval($val);
            if ($val == 0) {
                $val = '';
            }
            if (strlen($val) > 0) {
                if ($check) {           // No.以上チェック
                    $conds['Rss.entry_no >='] = $val;
                } else {
                    $conds['Rss.entry_no'] = $val;
                }
            }
            $this->data['Rss']['entry_no'] = $val;
        }

        // 一覧データの取得
        $searchResult = $this->paginate('Rss', $conds);
        $this->set('searchResult', $searchResult);
    }
}
