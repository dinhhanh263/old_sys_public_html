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

$table = "r_times_history";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : ($_POST['pay_date2'] ? $_POST['pay_date2'] : date("Y-m-d"));
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date2']." +1day"));


// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
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


$order = "pay_date";

if($_POST['shop_id']) $dWhere .= " AND  rt.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['type'] ) $dWhere .= " AND rt.type = '".addslashes($_POST['type'])."'";

// 月額コースID取得
// $month_id = implodeArray("course","id"," where del_flg=0 and type=1");

// if( $_POST['course']==1 ) $dWhere .= " AND rt.course_id in (".$month_id." )";
// if( $_POST['course']==2 ) $dWhere .= " AND rt.course_id not in (".$month_id." )";
if( $_POST['course']==3 ) $dWhere .= " AND rt.times >1 "; // 複数回コース  
if( $_POST['course']==4 ) $dWhere .= " AND rt.times =1 "; // 1回コース
if( $_POST['staff_id'] ) $dWhere .= " AND rt.staff_id = '".addslashes($_POST['staff_id'])."'";
if( $_POST['customer_id']){
	$dWhere .= " AND rt.customer_id='".$_POST['customer_id'] ."'";
}else{
	$dWhere .= " AND  rt.pay_date>='".$_POST['pay_date']."'";
	$dWhere .= " AND  rt.pay_date<='".$_POST['pay_date2']."'";
}

// データの取得------------------------------------------------------------------------

$dSql = "SELECT rt.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " rt,customer c WHERE rt.r_times>0 and rt.customer_id=c.id AND c.del_flg=0 AND c.ctype=1 AND rt.del_flg = 0".$dWhere." ORDER BY rt.".$order;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop("全店舗");

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" );
//$shop_list[0] = "全店舗";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//}

//staff list
$staff_list = getDatalist("staff","-",$where_shop);

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_times[$result['id']] = $result['times'];
	$course_length[$result['id']] = $result['length'];
}

?>