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

$table = "reservation";

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}
//ccstaff list
$ccstaff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 AND shop_id=999 ORDER BY id" );
$ccstaff_list[0] = "-";
while ( $result1 = $ccstaff_sql->fetch_assoc() ) {
	$ccstaff_list[$result1['id']] = $result1['name'];
}

// $mensdb = changedb();

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

//ADリスト
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by name" );
while ( $result = $adcode_sql->fetch_assoc() ) {
	$adcode_list[$result['id']] = $result['name'];
	$adcode_flyer_no[$result['id']] = $result['flyer_no'];
}
//------------------------------------------------------------------------------------
$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$_POST['hope_date2']=$_POST['hope_date2'] ? $_POST['hope_date2'] : ($_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['hope_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date']." +1day"));


// 検索条件の設定-------------------------------------------------------------------
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
if($_POST['shop_id'])$dWhere .= " AND  r.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND r.status = '".addslashes($_POST['status'])."'";
if( $_POST['type'] ) $dWhere .= " AND r.type = '".addslashes($_POST['type'])."'";
if( $_POST['hp_flg'] ) $dWhere .= " AND r.hp_flg = '".addslashes($_POST['hp_flg'])."'";
if( $_POST['ctype'] !=0 ) $dWhere .= " AND c.ctype = '".addslashes($_POST['ctype'])."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT r.*,c.no as no,c.name as name,c.name_kana as name_kana,c.mobile as mobile,c.tel as tel,c.ctype as ctype,c.adcode as ad FROM " . $table . " as r,customer as c WHERE r.customer_id=c.id AND c.del_flg = 0 AND r.del_flg = 0".$dWhere." ORDER BY r.type,r.hope_date ,r.hope_time ,r.reg_date DESC ";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);exit;
//csv export----------------------------------------------------------------------
$filename = "reservation_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("会員番号,名前,名前カナ,区分,店舗,コース,経由,媒体,広告番号,来店状況,来店日,来店時間,所要時間,施術主担当,施術サブ担当1,施術サブ担当2,契約状況,登録日\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' order by id desc");
		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gResType4[$data['type']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$contract['course_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gRoute[$data['route']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($adcode_list[$data['ad']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($adcode_flyer_no[$data['ad']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gBookStatus[$data['status']],"SJIS-win", "UTF-8")  . ","; // 来店状況
		echo $data['hope_date'] . ",";
		echo $gTime2[$data['hope_time']]. ",";
		echo mb_convert_encoding($gLength[$data['length']],"SJIS-win", "UTF-8"). ","; // 所要時間
		echo mb_convert_encoding($staff_list[$data['tstaff_id']],"SJIS-win", "UTF-8"). ","; // 施術主担当
		echo mb_convert_encoding($staff_list[$data['tstaff_sub1_id']],"SJIS-win", "UTF-8"). ","; // 施術サブ担当1
		echo mb_convert_encoding($staff_list[$data['tstaff_sub2_id']],"SJIS-win", "UTF-8"). ","; // 施術サブ担当2
		echo mb_convert_encoding(($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]),"SJIS-win", "UTF-8"). ","; // 契約状況
		echo $data['reg_date'] . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
