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

$table = "reservation";

$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : ($_POST['hope_date2'] ? $_POST['hope_date2'] : date("Y-m-01"));
$_POST['hope_date2']=$_POST['hope_date2'] ? $_POST['hope_date2'] : ($_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['hope_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date2']." +1day"));


// データの仮削除------------------------------------------------------------------------
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 9999;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}


$dWhere .= " AND  r.hope_date>='".$_POST['hope_date']."'";
$dWhere .= " AND  r.hope_date<='".$_POST['hope_date2']."'";
$order = "hope_date";

if($_POST['shop_id']) $dWhere .= " AND  r.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND r.status = '".addslashes($_POST['status'])."'";
if( $_POST['staff_id'] ) $dWhere .= " AND r.tstaff_id = '".addslashes($_POST['staff_id'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT r.id as reservation_id,r.customer_id,r.shop_id,r.hope_date,c.no as no,c.name as name,c.name_kana as name_kana,t.course_id,t.fixed_price,t.discount,t.latest_date,r.length,r.tstaff_id FROM " . $table . " r left join contract t on r.contract_id=t.id,customer c WHERE r.type=2 and r.status=11 and r.customer_id=c.id AND c.del_flg=0 AND r.del_flg = 0".$dWhere." ORDER BY r.".$order;


$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "全店舗";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}

// staff list
// if($_POST['shop_id']) $where_shop = " AND shop_id=".$_POST['shop_id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['hope_date']."') ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_times[$result['id']] = $result['times'];
	$course_length[$result['id']] = $result['length'];
	$course_price[$result['id']] = $result['price'];
}

?>