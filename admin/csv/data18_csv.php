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

// データの取得----------------------------------------------------------------------
$dSql = "
SELECT c.id '顧客ID', c.no '会員番号', c.name '名前', c.name_kana '名前カナ', h.name '契約店舗', u.name '契約コース', t.contract_date '契約日'
FROM customer c, contract t, course u, shop h
WHERE c.id = t.customer_id
AND u.id = t.course_id
AND h.id = t.shop_id
AND c.del_flg =0
AND t.del_flg =0
AND t.status =0
AND t.contract_date < CURDATE( ) 
AND c.ctype =1
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,会員番号,名前,名前カナ,契約店舗,契約コース,契約日\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['名前カナ'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約店舗'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約コース'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8") . ","; 
		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

