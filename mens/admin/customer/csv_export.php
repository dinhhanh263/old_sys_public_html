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

$table = "customer";

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
	$course_price[$result2['id']] = $result2['price'];
}

//ADリスト
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by name" );
while ( $result = $adcode_sql->fetch_assoc() ) {
	$adcode_list[$result['id']] = $result['name'];
	$adcode_flyer_no[$result['id']] = $result['flyer_no'];
}
//PREFリスト
$pref_sql = $GLOBALS['mysqldb']->query( "select * from prefectures order by id" );
while ( $result = $pref_sql->fetch_assoc() ) {
	$pref_list[$result['id']] = $result['name'];
}

//------------------------------------------------------------------------------------
if(!isset($_POST['ctype'])) $_POST['ctype'] =1;

$_POST['reg_date']=$_POST['reg_date'] ? $_POST['reg_date'] : ($_POST['reg_date2'] ? $_POST['reg_date2'] : date("2014-02-07"));
$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : date("Y-m-d");

$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : ($_POST['reg_date'] ? $_POST['reg_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['reg_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['reg_date2']." +1day"));

// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";

$dWhere .= " AND  reg_date>='".$_POST['reg_date']." 00:00:00'";
$dWhere .= " AND  reg_date<='".$_POST['reg_date2']." 23:59:59'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['adcode'] !=0 ) $dWhere .= " AND adcode = '".addslashes($_POST['adcode'])."'";
if( $_POST['rebook_flg'] !="" ) $dWhere .= " AND rebook_flg = '".addslashes($_POST['rebook_flg'])."'";
if( $_POST['route'] !="" ) $dWhere .= " AND route = '".addslashes($_POST['route'])."'";

$rWhere = $dWhere; // 最申込件数集計用

if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " or no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or address LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
if( $_POST['status'] !=0 ) $dWhere .= " AND status = '".addslashes($_POST['status'])."'";
if( $_POST['ctype'] !=0 ) $dWhere .= " AND ctype = '".addslashes($_POST['ctype'])."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0".$dWhere." ORDER BY reg_date DESC ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);exit;
//csv export----------------------------------------------------------------------
$filename = "customer_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("会員番号,名前,名前カナ,生年月日,経由,媒体,広告番号,予約日,予約時間,登録日,登録時間,都道府県,住所\n","SJIS-win", "UTF-8");
	//echo mb_convert_encoding("会員番号,名前,電話番号,生年月日,経由,媒体,予約日,予約時間,登録日,登録時間,都道府県,住所\n","SJIS-win", "UTF-8");//cc
	//echo mb_convert_encoding("会員番号,生年月日,経由,媒体,予約日,予約時間,登録日,登録時間,来店状況,契約金額,都道府県,住所\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {
		$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by hope_date desc,id desc limit 1");//最新予約

		$rsv2 = Get_Table_Row("reservation"," WHERE del_flg=0 and status<11 and customer_id = '".addslashes($data['id'])."' order by status desc,id desc limit 1");//

		list($reg_date,$reg_time) = explode(" ",  $data['reg_date']);
		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		//cc
		//echo mb_convert_encoding( ($data['name'] ? $data['name'] : $data['name_kana']),"SJIS-win", "UTF-8")  . ",";
		//echo $data['tel']  . ",";

		echo ($data['birthday']=="0000-00-00" ? "" : $data['birthday']) . ",";
		echo mb_convert_encoding($gRoute[$data['route']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($adcode_list[$data['adcode']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($adcode_flyer_no[$data['adcode']],"SJIS-win", "UTF-8")  . ",";
		echo $rsv['hope_date'] . ",";
		echo $gTime2[$rsv['hope_time']]. ",";
		echo $reg_date . ",";
		echo $reg_time . ",";

		//echo mb_convert_encoding($gBookStatus[$rsv2['status']],"SJIS-win", "UTF-8") . ",";
		//echo $course_price[$rsv['course_id']]. ",";

		echo mb_convert_encoding($pref_list[$data['pref']],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['address'],"SJIS-win", "UTF-8") . ","; 
		echo "\n";
	}

	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
