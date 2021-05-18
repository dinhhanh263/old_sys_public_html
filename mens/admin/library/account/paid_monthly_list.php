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
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  sales.pay_date>='".$pay_date1."'";
$dWhere .= " AND  sales.pay_date<='".$pay_date2."'";
if($_POST['shop_id']) $dWhere .= " AND  sales.shop_id='".$_POST['shop_id'] ."'";


// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(sales.id) FROM " . $table . ",customer WHERE sales.customer_id=customer.id AND customer.del_flg=0 AND sales.del_flg = 0 and sales.option_name=4 ".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql  = "SELECT sales.*,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel ";
$dSql .= "FROM " . $table . ",customer WHERE sales.customer_id=customer.id AND customer.del_flg=0 AND sales.del_flg = 0 and sales.option_name=4 ".$dWhere ." LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト----------------------------------------------------------------------------

$shop_list = getDatalist_shop("全店舗");
// $mensdb = changedb();

//courseリスト
$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}
?>
