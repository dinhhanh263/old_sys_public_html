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

// $table = "contract";
$table = "contract_P";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d");
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date']." +1day"));

// データの仮削除-----------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定-----------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定-----------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  tp.contract_date>='".$_POST['contract_date']."'";
$dWhere .= " AND  tp.contract_date<='".$_POST['contract_date2']."'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  tp.shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['status'] ) $dWhere .= " AND tp.status = '".addslashes($_POST['status'])."'";

// データの取得-----------------------------------------------------

$dSql = "SELECT count(c.id) FROM ".$table. " tp,customer c WHERE tp.customer_id=c.id AND tp.del_flg = 0 and tp.status=0  and tp.balance>0 ";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(c.id) FROM " . $table . " tp,customer c WHERE tp.customer_id=c.id AND tp.del_flg = 0  and tp.status=0 and tp.balance>0 ".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "
SELECT tp.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.reg_date as reg_date FROM " . $table . " tp,customer c ,
(
 SELECT tp.customer_id, max( tp.id ) AS mid
 FROM contract_P AS tp, (
  SELECT customer_id, max( contract_date ) AS contract_date
  FROM contract_P
  WHERE del_flg =0
  AND multiple_course_id <>0
  GROUP BY customer_id
 ) AS t2
 WHERE tp.contract_date = t2.contract_date
 AND tp.customer_id = t2.customer_id
 AND tp.del_flg =0
 AND tp.multiple_course_id <>0
 GROUP BY tp.customer_id
) AS v

WHERE tp.customer_id=c.id AND c.id = v.customer_id AND tp.id = v.mid AND c.del_flg = 0 AND tp.del_flg = 0 and tp.status in(0,7) and tp.balance<>0 ".$dWhere." ORDER BY tp.contract_date DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト-----------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();


//courseリスト
$course_list  = getDatalistMens("course");

?>