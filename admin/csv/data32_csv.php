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

$post_date1 = filter_input( INPUT_POST, "date1" );
$post_date2 = filter_input( INPUT_POST, "date2" );

$date1 = $post_date1 ? $post_date1 : date("Y-m-d");
$date2 = $post_date2 ? $post_date2 : date("Y-m-d");

// データの取得----------------------------------------------------------------------
$dSql = 'SELECT
	(SELECT code FROM staff WHERE id = staff_id) AS "従業員番号" ,
	(SELECT name FROM staff WHERE id = staff_id) AS "従業員氏名"  ,
	product_no AS "商品番号" ,
	(SELECT name FROM product WHERE id = product_no) AS "商品名" ,
	(SELECT price FROM product WHERE id = product_no) AS "販売単価" ,
	SUM(product_count) AS "販売個数" ,
	(SELECT price FROM product WHERE id = product_no) * SUM(product_count) AS "販売金額"
FROM product_stock WHERE del_flg = 0 AND use_status = 1 AND customer_id = 696180 AND SUBSTRING(pay_date, 1, 7 ) = "'.$date1.'" GROUP BY staff_id , product_no';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("従業員番号,従業員氏名,商品番号,商品名,販売単価,販売個数,販売金額\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['従業員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['従業員氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['商品番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['商品名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['販売単価'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['販売個数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['販売金額'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

