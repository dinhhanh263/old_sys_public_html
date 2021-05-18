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
$dSql = "
SELECT SUBSTRING( t.contract_date, 1, 7 ) 契約年月, h.name 店舗名, u.name AS コース, COUNT( t.id ) 契約数
FROM  `contract` t, course u, shop h
WHERE (t.status in(0,1,7) OR t.status in(2,3,4,6) AND SUBSTRING( t.contract_date, 1, 7 )<SUBSTRING( t.cancel_date, 1, 7 ))
AND t.del_flg =0
AND t.course_id = u.id
AND t.shop_id = h.id
AND SUBSTRING( t.contract_date, 1, 7 ) >=  '".$date1."'
GROUP BY SUBSTRING( t.contract_date, 1, 7 ) , t.shop_id, u.name
ORDER BY SUBSTRING( t.contract_date, 1, 7 ) , h.area, h.pref, h.name, u.name

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("契約年月,店舗名,コース,契約数\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['契約年月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コース'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約数'],"SJIS-win", "UTF-8") . ","; 
		echo "\n";

	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

