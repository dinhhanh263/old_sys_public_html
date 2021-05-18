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

$table = "sales";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y/m");
$pay_date1 = str_replace( "/", "-",$_POST['pay_date'])."-01";
$pay_date2 = str_replace( "/", "-",$_POST['pay_date'])."-".date('t', mktime(0, 0, 0, substr($_POST['pay_date'],5), 1, substr($_POST['pay_date'],0,4)));

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 500000;
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
$dWhere .= " AND  s.pay_date>='".$pay_date1."'";
$dWhere .= " AND  s.pay_date<='".$pay_date2."'";
if($_POST['shop_id']) $dWhere .= " AND  s.shop_id='".$_POST['shop_id'] ."'";
// 月額コースID取得
$old_month_id = implodeArray("course","id"," where del_flg=0 and type=1 and new_flg=0"); // 旧月額
$new_month_id = implodeArray("course","id"," where del_flg=0 and type=1 and new_flg=1"); // 新月額
if( $_POST['course']==1 ) $dWhere .= " AND s.course_id in (".$old_month_id." )"; // 旧月額
if( $_POST['course']==3 ) $dWhere .= " AND s.course_id in (".$new_month_id." )"; // 新月額


// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(s.id) FROM " . $table . " s,customer c WHERE s.customer_id=c.id AND c.del_flg=0 AND s.del_flg = 0 and s.option_name=4 ".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql  = "SELECT s.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel ";
$dSql .= "FROM " . $table . " s,customer c WHERE s.customer_id=c.id AND c.del_flg=0 AND s.del_flg = 0 and s.option_name=4 ".$dWhere ." LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト----------------------------------------------------------------------------

$shop_list = getDatalist("shop","全店舗");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//courseリスト
$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}
?>
