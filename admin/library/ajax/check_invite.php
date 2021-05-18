<?php
  header('Content-Type: application/json; charset=utf-8');
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
  include_once( "../../../lib/member.php" );
require_once LIB_DIR . 'function.php';

  $inviteCode = isset($_POST['inviteCode'])? $_POST['inviteCode'] : '';

  if ( !empty($inviteCode) ) {

		// 半角空白・全角空白を除去する
		$inviteCode = str_replace(array(" ", "　"), "", $inviteCode);

		// 全角英数字を半角英数字に変換する
		$inviteCode = mb_convert_kana($inviteCode, 'rna');

    $customer = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" .  $GLOBALS['mysqldb']->real_escape_string($inviteCode) . "'");

    if ( isset($customer) && $customer !== false ) {
      echo json_encode('紹介元顧客情報：' . $customer['name'] . '（ID:<a href="edit.php?customer_id=' . $customer['id'] . '" target="_blank">' . $customer['id'] . '</a>）');
    } else {
      echo json_encode('該当する顧客が見つかりません。');
    }

    exit;

  } else {
    echo json_encode('');
  }
