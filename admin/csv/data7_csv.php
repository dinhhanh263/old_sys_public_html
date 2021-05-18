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
SELECT c.id 顧客ID, c.no 会員番号, c.name 名前, u.name コース, t.contract_date 契約日, t.latest_date 直近お手入れ日, (
t.times - t.r_times
)残回数
FROM customer c, contract t, course u
WHERE c.del_flg =0
AND t.del_flg =0
AND t.customer_id = c.id
AND t.course_id = u.id
AND c.ctype =1
AND t.status =0
AND u.type =0
AND t.r_times >0
AND t.latest_date < DATE_FORMAT( ADDDATE( NOW( ) , INTERVAL -3
MONTH ) ,  '%Y-%m-%d' )
AND t.contract_date < DATE_FORMAT( ADDDATE( NOW( ) , INTERVAL -3
MONTH ) ,  '%Y-%m-%d' )
ORDER BY  `t`.`latest_date` DESC

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,会員番号,名前,コース,契約日,直近お手入れ日\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コース'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['直近お手入れ日'],"SJIS-win", "UTF-8") . ",";
		echo "\n";

	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

