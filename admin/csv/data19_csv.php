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
SELECT SUBSTR( r.hope_date, 1, 7 ) 予約年月, h.name 店舗, 
CASE u.type
WHEN 0 THEN 'パック'
WHEN 1 THEN '月額'
END '契約種別',
u.name 契約コース, COUNT( r.id ) 施術人数
FROM  `reservation` r, shop h, course u
WHERE r.del_flg =0
AND r.shop_id = h.id
AND r.course_id = u.id
AND r.type =2
AND r.status =11
AND r.length >1
AND SUBSTR( r.hope_date, 1, 7 ) =  '".$date1."'
GROUP BY r.shop_id, r.course_id

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("予約年月,店舗,契約種別,契約コース,施術人数\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['予約年月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約種別'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約コース'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['施術人数'],"SJIS-win", "UTF-8") . ","; 

		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}
