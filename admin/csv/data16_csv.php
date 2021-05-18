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
SELECT SUBSTRING( r.hope_date, 1, 7 ) AS '予約年月', h.name AS '店舗', h.counseling_rooms AS 'カウンセリングルーム数', (SUM( r.length ) * 0.5) AS 'C_不可等の時間数', ((
h.counseling_rooms *20 *".date('t', strtotime($date1))." - SUM( r.length )
) * 0.5) AS 'C_予約可能時間数'
FROM  `reservation` r, customer c, shop h
WHERE c.del_flg =0
AND r.del_flg =0
AND c.id = r.customer_id
AND c.ctype =5
AND r.type <>3
AND r.shop_id = h.id
AND SUBSTRING( r.room_id, 1, 1 ) in(1,2)
AND SUBSTRING( r.room_id, 2 ) >= 1
AND SUBSTRING( r.room_id, 2 ) <= cast(h.medical_rooms AS SIGNED) +cast(h.vip_rooms AS SIGNED)
AND SUBSTRING( r.hope_date, 1, 7 ) =  '".$date1."'
GROUP BY r.shop_id
ORDER BY h.area, h.pref, h.name

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("予約年月,店舗,カウンセリングルーム数,C_不可等の時間数,C_予約可能時間数\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['予約年月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['カウンセリングルーム数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['C_不可等の時間数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['C_予約可能時間数'],"SJIS-win", "UTF-8") . ","; 

		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

