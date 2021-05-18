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
include_once( "../../lib/auth.php" );

session_start();

$table = "introducer";
$column_name = "refund_request";
$action_name = str_replace(".php", basename(__FILE__));

if ($_POST['checks'] == null) {
  $_SESSION['error'][$action_name] = "更新対象の行に1つ以上チェックを入れてください。";
  header("location: index.php");
}

// 検索条件の設定-------------------------------------------------------------------

$dWhere = " id in (";
for ($i = 0; $i < count($_POST['checks']); $i++ ) {
  $dWhere.= $_POST['checks'][$i];
  if ($i !== count($_POST['checks']) - 1) $dWhere.= ',';
}
$dWhere.= ")";

// データの更新----------------------------------------------------------------------

$sql = "SELECT `" . $column_name . "` FROM " . $table . " WHERE del_flg = 0 AND" . $dWhere;
$result = $GLOBALS['mysqldb']->query( $sql );
$updateCheck = true;
while ( $row = $result->fetch_assoc() ) {
  if ($row[$column_name] !== '0000-00-00' ) {
    $updateCheck = false;
    break;
  }
}

if ($updateCheck) {

  $sql = "UPDATE `" . $table . "` SET ";
  $sql.= "`" . $column_name . "` = now() WHERE" . $dWhere;

  $result = $GLOBALS['mysqldb']->query( $sql );

  if ($result) {
    $_SESSION['success'][$action_name] = "データの更新が完了しました。";
  } else {
    $_SESSION['error'][$action_name] = "更新対象の行に1つ以上チェックを入れてください。";
  }

} else {
  $_SESSION['error'][$action_name] = "既に日付が設定されているデータを含むため処理を中断しました。";
}

header("location: index.php");