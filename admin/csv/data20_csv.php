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
SELECT
CASE
WHEN u.type=0 THEN 'パック'
WHEN u.type=1 THEN '月額'
END コース, h.name '店舗', COUNT( t.id ) '解約数'
FROM  `contract` t, course u,shop h
WHERE t.del_flg =0
AND u.id = t.course_id
AND h.id = t.shop_id
AND t.status
IN ( 2, 3, 6 )
AND SUBSTRING( t.contract_date, 1, 7 ) =  '".$date1."'
AND SUBSTRING( t.cancel_date, 1, 7 ) =  '".$date1."'
GROUP BY u.type,t.shop_id
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("コース,店舗,解約数\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['コース'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['解約数'],"SJIS-win", "UTF-8")  . ",";

		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

