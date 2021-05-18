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
$dSql = 'Select
  r.id,
  r.hope_date "来店日",
  r.customer_id,
  c.no "会員番号",
  r.shop_id "店舗ID",
  s.name "店舗名",
  r.course_id "コースID",
  o.name "コース名",
  o.type,
  o.new_flg,
  r.tstaff_id "スタッフID",
  t.name "スタッフ名",
  t.shop_id"所属店舗ID",
  t.shop_id "所属店舗名",
  t.code "従業員コード",
  r.length,
  r.length*30 "施術時間（分）"
From
  reservation r Left join customer c On r.customer_id = c.id
  Left join shop s On r.shop_id = s.id
  Left join course o On r.course_id = o.id
  Left join staff t On r.tstaff_id = t.id
Where
  r.del_flg = 0
  And r.type = 2
  AND SUBSTRING( r.hope_date, 1, 7 ) =  "'.$date1.'"
  And r.status >= 2
';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("id,来店日,customer_id,会員番号,店舗ID,店舗名,コースID,コース名,type,new_flg,スタッフID,スタッフ名,所属店舗ID,所属店舗名,従業員コード,length,施術時間（分）\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['id'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['来店日'],"SJIS-win", "UTF-8")  . ",";
    	echo mb_convert_encoding($data['customer_id'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['店舗ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コースID'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['コース名'],"SJIS-win", "UTF-8"). ",";
    	echo mb_convert_encoding($data['type'],"SJIS-win", "UTF-8"). ",";
    	echo mb_convert_encoding($data['new_flg'],"SJIS-win", "UTF-8"). ",";
    	echo mb_convert_encoding($data['スタッフID'],"SJIS-win", "UTF-8"). ",";
    	echo mb_convert_encoding($data['スタッフ名'],"SJIS-win", "UTF-8"). ",";
    	echo mb_convert_encoding($data['所属店舗ID'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['所属店舗名'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['従業員コード'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['length'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['施術時間（分）'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

