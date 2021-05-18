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
SELECT c2.id '顧客ID', c.id '契約ID', c2.no '会員番号', c2.name '名前', c.staff_id '契約テーブルのスタッフID', st1.name '契約テーブルのスタッフ名', s.staff_id '売上テーブルのスタッフID', st2.name '売上テーブルのスタッフ名', s.pay_date '支払日'
FROM contract AS c, sales AS s, customer AS c2, staff as st1, staff as st2
WHERE
  c.id = s.contract_id AND
  c.del_flg =0 AND
  s.del_flg =0 AND
  c2.del_flg =0 AND
  c2.ctype =1 AND
  s.customer_id = c2.id AND
  c.staff_id <> s.staff_id AND
  s.type =1 AND
  c.contract_date > date_add( now( ) , INTERVAL-3 MONTH ) AND
  st1.del_flg =0 AND
  st2.del_flg =0 AND
  c.staff_id = st1.id AND
  s.staff_id = st2.id
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,契約ID,会員番号,名前,契約テーブルのスタッフID,契約テーブルのスタッフ名,売上テーブルのスタッフID,売上テーブルのスタッフ名,支払日\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['契約テーブルのスタッフID'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['契約テーブルのスタッフ名'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['売上テーブルのスタッフID'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['売上テーブルのスタッフ名'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['支払日'],"SJIS-win", "UTF-8") . ",";
		echo "\n";

	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

