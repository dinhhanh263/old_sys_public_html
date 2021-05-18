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

$_POST['date1'] = $_POST['date1'] ? $_POST['date1'] : date("Y-m-d");
$_POST['date2'] = $_POST['date2'] ? $_POST['date2'] : date("Y-m-d");

// データの取得----------------------------------------------------------------------
$dSql = "
SELECT
    c.name AS '契約者氏名'
  , c.name_kana AS '名前カナ'
  , c.no AS '会員番号'
  , t.contract_date AS '契約日'
  , t.start_ym AS '新月額施術開始年月'
  , u.name AS '契約コース'
  , CASE t.pay_type 
    WHEN '2' THEN 'クレジット引落' 
    WHEN '3' THEN '銀行引落'
    ELSE '未' 
    END as '支払方法' 
FROM
  contract t 
  INNER JOIN customer c 
    ON t.customer_id = c.id 
  INNER JOIN course u 
    ON t.course_id = u.id 
WHERE
  t.status = 0 
  AND u.type = 1 
  AND u.new_flg = 1 
  AND t.contract_date BETWEEN '".$_POST['date1']."' AND '".$_POST['date2']."'
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.mysql_error());

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("契約者氏名,名前カナ,会員番号,契約日,新月額施術開始年月,契約コース,支払方法\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc()  ) {

		echo mb_convert_encoding($data['契約者氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前カナ'],"SJIS-win", "UTF-8")  . ",";
		echo $data['会員番号'] . ",";
		echo $data['契約日']. ",";
		echo $data['新月額施術開始年月']. ",";
		echo mb_convert_encoding($data['契約コース'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['支払方法'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
