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
//$table = "contract";
$table = "contract_P";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-01"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));


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

// 支払完了
if($_POST['if_balance']==1){
	$dWhere .= " AND tp.pay_complete_date>='".$_POST['contract_date']."'";
	$dWhere .= " AND tp.pay_complete_date<='".$_POST['contract_date2']."'";
	$dWhere .= " AND tp.balance<=0";
	$order = "pay_complete_date";
}else{
	$dWhere .= " AND  tp.contract_date>='".$_POST['contract_date']."'";
	$dWhere .= " AND  tp.contract_date<='".$_POST['contract_date2']."'";
	$order = "contract_date";
}

// 残金あり
if($_POST['if_balance']==2){
	$dWhere .= " AND tp.balance>0";
}

if( $_POST['shop_id'] ) $dWhere .= " AND  tp.shop_id='".($_POST['shop_id'] )."'";
//if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";
if( $_POST['staff_id'] ) $dWhere .= " AND tp.staff_id = '".addslashes($_POST['staff_id'])."'";

// データの取得------------------------------------------------------------------------
// プラン変更前のデータ除外
// $dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " t,customer c WHERE tp.customer_id=c.id AND t.status=0 AND c.del_flg=0 AND t.del_flg = 0".$dWhere." ORDER BY t.".$order;
$dSql = "SELECT tp.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " tp,customer c WHERE tp.customer_id=c.id AND tp.status=0 AND c.del_flg=0 AND tp.del_flg = 0".$dWhere." ORDER BY tp.".$order;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );


// 未契約数取得 status=2----------------------------------------------------------------------

// $dSql = "SELECT count(r.id) FROM reservation r WHERE r.del_flg=0 AND r.status=2 AND r.type=1 AND r.hope_date>='".$_POST['contract_date']."' AND hope_date<='".$_POST['contract_date2']."'";
// $dSql .= " AND NOT EXISTS (SELECT r2.customer_id FROM reservation r2 WHERE r2.del_flg =0 AND r2.customer_id = r.customer_id AND r2.hope_date > now()) "; // 未来日の予約を除外する 20160804 shimada
// if($_POST['shop_id'] )$dSql .= " AND shop_id='".$_POST['shop_id'] ."'";
// if( $_POST['staff_id'] ) $dSql .= " AND cstaff_id = '".addslashes($_POST['staff_id'])."'";
// $dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
// $dGet_Cnt = $dRtn2->fetch_row()[0];

// 未契約数取得 ----------------------------------------------------------------------
	// r.status=2 => r.status>=2 //その他での来店可能性も考慮　add by ka 20151211
	// 未来日に予約がある方は除外 add by ka 20151211
	// 売上一覧と同じSQLで未契約者を取得する方針で修正 add by shimada 20160823

	$dSql = "SELECT COUNT( DISTINCT r.customer_id ) 
			FROM customer c, reservation r
			WHERE c.del_flg =0
			AND r.del_flg =0
			AND c.id = r.customer_id
			AND c.ctype =1
			AND r.status >=2
			AND r.hope_date>='".$_POST['contract_date']."' AND r.hope_date<='".$_POST['contract_date2']."'
			AND NOT EXISTS (SELECT t.customer_id FROM contract t WHERE t.del_flg =0 AND c.id = t.customer_id)
			AND NOT EXISTS (SELECT r2.customer_id FROM reservation r2 WHERE r2.del_flg =0 AND r2.customer_id = r.customer_id AND  r2.hope_date > now())";
	if($_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'"; 
	if( $_POST['staff_id'] ) $dSql .= " AND r.cstaff_id = '".addslashes($_POST['staff_id'])."'";
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

// 店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['contract_date']."') ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>