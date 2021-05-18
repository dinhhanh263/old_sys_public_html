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
SELECT
  COUNT(t_all.customer_id) as '月額総数',
  COUNT(u.new_flg = 0 or null) as '旧総数',
  COUNT(u.new_flg = 1 or null) as '新総数'
FROM contract as t_all
  INNER JOIN course as u
WHERE
  t_all.del_flg = 0 AND
  u.del_flg = 0 AND
  t_all.status = 0 AND
  t_all.course_id = u.id AND
  u.type = 1
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("月額総数,旧総数,新総数\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
  	echo $data['月額総数'] . ",";
		echo $data['旧総数']. ",";
		echo $data['新総数']. ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

