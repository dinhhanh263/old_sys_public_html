<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "customer";

if ($_GET['customer_id'] != "") {
	$customer = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . h($_GET['customer_id']) . "'");

	if ($customer['id'] != "") {
		$shop = Get_Table_Row("shop", " WHERE del_flg=0 AND id = '" . h($customer['shop_id']) . "'");

		// マイページ出力用（パスワードのカナ変換対応）
		$str_kana_fields = preg_split("//u", $customer['password'], -1, PREG_SPLIT_NO_EMPTY);
		$kana = array();
		foreach ($str_kana_fields as $key => $s_val) {
			// str_kanaテーブルの対応表から検索する
			$kana = Get_Table_Row("str_kana", " WHERE str = '" . addslashes($str_kana_fields[$key]) . "'");
			$mp_kana[] = $kana['kana'];
		}
		// マイページのパスワード（カタカナ）
		if (empty($mp_kana)) {
			$mp_kana = '';
		} else {
			$mp_kana = implode(' ', $mp_kana);
		}
	}
}