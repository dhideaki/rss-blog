<?php
/**
 * アプリケーション基本コントローラークラス
 *
 * 各コントローラーの共通処理
 *
 * @author H.Asakura
 * @version $Id$
 */
class AppController extends Controller
{
    // 使用するコンポーネントクラス
    var $components = array('Security', 'Session', 'Cookie');

    // 使用するヘルパークラス
    var $helpers = array('Html', 'Form', 'Session', 'Js'=>array('jquery'), 'Time');

    /**
     * beforeFilter処理
     *
     * コントローラー処理開始前のコールバック処理
     *
     * @return void
     */
    function beforeFilter()
    {
        // CSRF対策 トークンチェック
        $this->Security->blackHoleCallback = '_blacHole';    // 不正リクエストに対して行う処理
    }

    /**
     * トークン認証不正
     *
     * Securityコンポーネント、不正リクエストに対する処理
     *
     * @return void
     */
    function _blackHole()
    {
        $this->cakeError('error404');    // 404エラー表示
    }

    /**
     * パラメータ確認
     *
     * 偽装フォームによるパラメータの改ざん対策
     *
     * @param $model モデル名
     * @param $key パラメータ名
     * @return パラメータ値
     */
    protected function getDataParam($model, $key)
    {
        if (isset($this->data[$model][$key])) {
            $val = strval($this->data[$model][$key]);
            if ($val === '0' || !empty($val)) {
                return $val;
            }
        }
        return '';
    }

    /**
     * 期間検索共通処理
     *
     * 日時フィールドから検索文字列を返却する
     * フォームデータの再設定を行う
     * ・strtotimeにて丸めた日時を設定する
     * ；日時が不正の場合空にする
     *
     * @param $data フォームデータ
     * @param $keys 開始、終了のフィールド名
     * @param $model モデル名
     * @param $field フィールド名
     * @return 検索文字列
     */
    protected function searchDate(&$data, $keys, $model, $field)
    {
        $rets = array();
        $arrDays = array();

        // 引数チェック
        if (!is_array($keys) || count($keys) != 2) {
            return false;
        }
        $start  = reset($keys);     // 開始フィールド名
        $end    = next($keys);      // 終了フィールド名

        // 各フィールドの日時を文字とタイムスタンプに設定
        foreach ($keys as $key) {
            $arrDays[$key]['str'] = $this->$model->deconstruct($field, $data[$key]);
            $arrDays[$key]['ts']  = strtotime($arrDays[$key]['str']);
        }

        // 開始 > 終了 の場合、開始と終了を入れ替える
//        if ($arrDays[$end]['ts'] && $arrDays[$start]['ts'] > $arrDays[$end]['ts']) {
//            list($arrDays[$start], $arrDays[$end]) = array($arrDays[$end], $arrDays[$start]);
//        }

        // 日時フィールドに再設定、検索文字列設定
        foreach ($keys as $key) {
            $ts = $arrDays[$key]['ts'];
            $arr = array('year' => 'Y', 'month' => 'm', 'day' => 'd', 'hour' => 'H', 'min' => 'i');
            // 日時フィールドに再設定
            foreach ($data[$key] as $k => $val) {
                $data[$key][$k] = '';       // 一度空にする
                if (!empty($ts) && !empty($arr[$k])) {
                    $data[$key][$k] = date($arr[$k], $ts);
                }
            }
            // 検索文字列設定
            if (!empty($ts)) {
                $rets[$key] = $arrDays[$key]['str'];
            }
        }
        return $rets;
    }
}
