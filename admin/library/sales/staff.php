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

$table = "contract";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-01"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));


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

// 支払完了
// ソート設定：なし
if($_POST['if_sort']==0){
	// 自動ソート：支払完了日順
	// 残金状況：支払完了
if($_POST['if_balance']==1){
	$order = "pay_complete_date";
	}
	// 自動ソート：契約日順
	// 契約区分：クーリングオフ、中途解約、自動解約以外
	elseif($_POST['status']<>2 && $_POST['status']<>3 && $_POST['status']<>6){
	$order = "contract_date";
}
	// 自動ソート：解約日順
	// 契約区分：クーリングオフ、中途解約、自動解約
	elseif($_POST['status']==2 || $_POST['status']==3 || $_POST['status']==6){
		$order = "cancel_date";
	}
}
// ソート設定：あり
// ソート設定：契約日順
elseif($_POST['if_sort']==1){
	$order = "contract_date";
}
// ソート設定：解約日順
elseif($_POST['if_sort']==2){
	$order = "cancel_date";
}
// ソート設定：支払完了日順
elseif($_POST['if_sort']==3){
	$order = "pay_complete_date";
}
$dWhere .= " AND t.".$order.">='".$_POST['contract_date']."'";
$dWhere .= " AND t.".$order."<='".$_POST['contract_date2']."'";

// 残金あり
if($_POST['if_balance']==1){
	$order = $order;
}
// 【残金状況】残金あり
elseif($_POST['if_balance']==2){
	$dWhere .= " AND t.balance>0";
}

//---------------------------------------------------------------------------------------------

if( $_POST['shop_id'] ) $dWhere .= " AND  t.shop_id='".($_POST['shop_id'] )."'";
//if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";
if( -1 < $_POST['status'])$dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";
if( $_POST['staff_id'] ) $dWhere .= " AND t.staff_id = '".addslashes($_POST['staff_id'])."'";

// データの取得------------------------------------------------------------------------
// プラン変更前のデータ除外
// $dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " t,customer c WHERE t.customer_id=c.id AND t.status=0 AND c.del_flg=0 AND t.del_flg = 0".$dWhere." ORDER BY t.".$order;
// 2016/09/27下記条件に変更
// AND t.status=0を検索条件から除外 
// AND t.status<>5追加(契約中の条件削除、ローン取消以外追加) 
// AND NOT(t.status=0 AND 1000<t.course_id)追加(契約中で返金保証期間終了後のコースは除外)
$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " t,customer c WHERE t.customer_id=c.id AND c.del_flg=0 AND t.del_flg = 0 AND t.status<>5 AND NOT(t.status=0 AND 1000<t.course_id)".$dWhere." ORDER BY t.".$order;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);


// 未契約数取得 status=2----------------------------------------------------------------------
// 区分：一般
// 予約区分：カウンセリング
// 来店状況：未契約
// 同お客さまで契約がなく、未来の予約も入っていないこと
$dSql = "SELECT count(r.id) FROM reservation r,customer c WHERE r.del_flg=0 AND r.status=2 AND r.type=1 AND r.hope_date>='".$_POST['contract_date']."' AND r.hope_date<='".$_POST['contract_date2']."'";
$dSql .= "  AND r.customer_id=c.id AND c.del_flg=0 AND c.ctype=1 ";
$dSql .= "	AND NOT EXISTS (SELECT t.customer_id FROM contract t WHERE t.del_flg =0 AND c.id = t.customer_id)";
		//	AND NOT EXISTS (SELECT r2.customer_id FROM reservation r2 WHERE r2.del_flg =0 AND r2.customer_id = r.customer_id AND  r2.hope_date > now())
if( $_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['staff_id'] ) $dSql .= " AND r.cstaff_id = '".addslashes($_POST['staff_id'])."'";

$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

// 店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['contract_date']."') ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>