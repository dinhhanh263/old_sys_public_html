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

$_POST['give_date'] = $_POST['give_date']  ? $_POST['give_date']  : ($_POST['give_date2'] ? $_POST['give_date2'] : date("2018-05-08"));
$_POST['give_date2']=$_POST['give_date2'] ? $_POST['give_date2'] : date("Y-m-d");

$pre_date = date("Y-m-d", strtotime($_POST['give_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['give_date']." +1day"));

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
if($_POST['search_shop_id'] !=0) $dWhere .= " AND c.shop_id='".($_POST['search_shop_id'])."'";

$dWhere .= " AND SUBSTRING(b.give_date,1,10)>='".$_POST['give_date']."'";
$dWhere .= " AND SUBSTRING(b.give_date,1,10)<='".$_POST['give_date2']."'";

$from = "FROM customer AS c,virtual_bank AS b WHERE c.id=b.customer_id AND c.del_flg =0 AND b.del_flg =0 AND b.give_flg=1 ";
// データの取得----------------------------------------------------------------------
$dSql = "SELECT b.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.mail as mail ".$from.$dWhere."order by b.give_date DESC,id DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "account_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3 ) {
	echo mb_convert_encoding("会員番号,名前,名前カナ,電話番号,メールアドレス,支店名,支店番号,バーチャル口座番号,付与日時\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['tel'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['mail'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['branch_name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['branch_no'],"SJIS-win", "UTF-8")  . ",";
		echo $data['virtual_no'] . ",";
		echo $data['give_date'] . ",";
		echo "\n";
	}

	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
