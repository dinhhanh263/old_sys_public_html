<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

//店舗リスト------------------------------------------------------------------------
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 ORDER BY id" );
$shop_list[1001] = "本社";
$shop_list[1002] = "本社研修センター";
$shop_list[1003] = "大阪研修センター";
$shop_list[999] = "コールセンター";

$shop_alias[11001] = "本";
$shop_alias[4] = "☓";
$shop_alias[5] = "★";
$shop_alias[6] = "欠";
$shop_alias[8] = "刻";
$shop_alias[9] = "退";
$shop_alias[37] = "特休";
$shop_alias[38] = "代";
$shop_alias[39] = "イ";
$shop_alias[40] = "半前";
$shop_alias[41] = "半後";
$shop_alias[11] = "有";
$shop_alias[12] = "忌";
$shop_alias[19] = "半";
$shop_alias[110] = "結婚";
$shop_alias[10999] = "CC";
$shop_alias[11002] = "本研";
$shop_alias[11003] = "大研";
//shift_code<=12 => shift_code<=22
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
	$alias_id = 10000 + $result['id'];//30->10000
	$shop_alias[$alias_id] = $result['name_alias'];
	$alias_list[$result['name_alias']] = $result['name'];
}

//各店舗略名をリストに追加
if($_POST['shop_id']=="1001")$gShiftType = $shop_alias;

if(!$_POST['shop_id']) $_POST['shop_id'] = 1010;
$table = "shift";

$_POST['shift_month']=$_POST['shift_month'] ? substr($_POST['shift_month'],0,7) : date("Y-m");
$pre_month = date("Y-m", strtotime($_POST['shift_month']." -1 month"));
$next_month = date("Y-m", strtotime($_POST['shift_month']." +1 month"));

$current_month =  date("Y-n",strtotime($_POST['shift_month'])); //月:先頭にゼロをつけない。
$end_date = date("Y-m-t",strtotime($_POST['shift_month']));
$end_date2 = date("Y-m-d",strtotime($_POST['shift_month']));
$days = substr($end_date,-2);

// データの新規登録-----------------------------------------------------------------
if( $_POST['action'] == "new" ){
	$_POST['reg_date'] = date("Y-m-d H:i:s");
	Input_Data($table);
}

// データの変更--------------------------------------------------------------------
if( $_POST['action'] == "edit" && $_POST['id']){
	$_POST['edit_date'] = date("Y-m-d H:i:s");
	Input_Update_Data($table);
}

// データの仮削除
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
if($_POST['shop_id']) $dWhere .= " AND shop_id='".$_POST['shop_id'] ."'";
if($_POST['shift_month']) $dWhere .= " AND shift_month='".$_POST['shift_month'] ."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY staff_id ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//staffリスト
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0" ) or die('query error'.$GLOBALS['mysqldb']->error);

while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_type[$result['id']] = $result['type'];

}
//出勤に反映するパターン
$work = array(1,2,3,8,9,10,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,51,52,53,54,55,56,101,102,103,104,105,106);
$notice = array(6,13,14,15,16,17,18,35,36,53,54,55,56);
?>
