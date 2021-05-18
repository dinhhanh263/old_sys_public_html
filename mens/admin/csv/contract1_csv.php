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


$_POST['date1'] = $_POST['date1'] ? $_POST['date1'] : date("Y-m-d");
$_POST['date2'] = $_POST['date2'] ? $_POST['date2'] : date("Y-m-d");

// データの取得----------------------------------------------------------------------
$dSql = "
SELECT SUBSTRING( t.contract_date, 1, 7 ) 年月, h.name 店舗名, u.name AS コース, t.pid AS '契約番号',COUNT( t.id ) 契約数
FROM  `contract` t, course u, shop h
WHERE (
t.status
IN ( 0, 1, 7 ) 
OR t.status
IN ( 2, 3, 6 ) 
AND SUBSTRING( t.contract_date, 1, 7 ) < SUBSTRING( t.cancel_date, 1, 7 )
)
AND t.del_flg =0
AND t.course_id = u.id
AND t.shop_id = h.id
AND u.id < 1000
GROUP BY SUBSTRING( t.contract_date, 1, 7 ) , t.shop_id, u.name
ORDER BY SUBSTRING( t.contract_date, 1, 7 ) , h.area, h.pref, h.name, u.name
";


$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "cpd_mon_change.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("年月,店舗名,コース,契約番号,契約数\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {
  	echo mb_convert_encoding($data['年月'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['店舗名'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['コース'],"SJIS-win", "UTF-8"). ",";
    echo $data['契約番号']. ",";
    echo $data['契約数']. ",";
		echo "\n";
	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
