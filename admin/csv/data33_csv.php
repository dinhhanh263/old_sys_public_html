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
include_once( "../../lib/auth.php" );

$post_date1 = filter_input( INPUT_POST, "date1" );
$post_date2 = filter_input( INPUT_POST, "date2" );

$date1 = $post_date1 ? $post_date1 : date("Y-m-d");
$date2 = $post_date2 ? $post_date2 : date("Y-m-d");


// データの取得----------------------------------------------------------------------
$dSql = "SELECT
	(SELECT no FROM customer WHERE id = customer_id) AS '顧客番号' ,
	(SELECT name FROM customer WHERE id = customer_id) AS '顧客氏名' ,
	(SELECT name_kana FROM customer WHERE id = customer_id) AS '顧客氏名(カナ)' ,
	(SELECT birthday FROM customer WHERE id = customer_id) AS '生年月日' ,
	TIMESTAMPDIFF(YEAR, (SELECT birthday FROM customer WHERE id = customer_id) , contract_date) AS '契約時年齢' ,
	contract_date AS '契約日' ,
	(SELECT name FROM shop WHERE id = shop_id) AS '契約店舗'
FROM contract WHERE del_flg = 0 AND TIMESTAMPDIFF(YEAR, (SELECT birthday FROM customer WHERE id = customer_id) , contract_date) < 20 AND contract_date BETWEEN '".$date1."' AND '".$date2."'
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客番号,顧客氏名,顧客氏名(カナ),生年月日,契約時年齢,契約日,契約店舗\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo $data['顧客番号'] . ",";
		echo mb_convert_encoding($data['顧客氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['顧客氏名(カナ)'],"SJIS-win", "UTF-8")  . ",";
		echo $data['生年月日'] . ",";
		echo $data['契約時年齢'] . ",";
		echo $data['契約日'] . ",";
    	echo mb_convert_encoding($data['契約店舗'],"SJIS-win", "UTF-8")  . ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

