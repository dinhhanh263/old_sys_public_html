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
  r.id AS "予約番号",
  r.hope_date "来店日",
  c.no "会員番号",
  c.name "会員氏名",
  s.name "店舗名",
  o.name "コース名",
  t.name "スタッフ名",
  t.code "スタッフ番号",
  r.length * 30 "施術時間",
  r.sales_id "請求番号",
  CASE
	WHEN r.status = 1 THEN "来店なし"
	WHEN r.status = 2 THEN "未契約"
	WHEN r.status = 3 THEN "契約月額"
	WHEN r.status = 4 THEN "契約６回"
	WHEN r.status = 5 THEN "契約10回"
	WHEN r.status = 6 THEN "契約12回"
	WHEN r.status = 7 THEN "契約15回"
	WHEN r.status = 8 THEN "契約18回"
	WHEN r.status = 9 THEN "カスタマイズ月額"
	WHEN r.status = 10 THEN "カスタマイズパック"
	WHEN r.status = 11 THEN "来店"
	WHEN r.status = 14 THEN "体験"
	WHEN r.status = 16 THEN "全身月額プラン"
	WHEN r.status = 17 THEN "全身1年プラン"
	WHEN r.status = 18 THEN "全身2年プラン"
	WHEN r.status = 19 THEN "全身SPプラン"
	WHEN r.status = 20 THEN "平日とく得1年プラン"
	WHEN r.status = 21 THEN "平日とく得2年プラン"
	WHEN r.status = 22 THEN "平日とく得スペシャルプラン"
	WHEN r.status = 23 THEN "U-19応援プラン"
	WHEN r.status = 24 THEN "全身お試しプラン"
	WHEN r.status = 25 THEN "全身10回プラン"
	WHEN r.status = 26 THEN "全身15回プラン"
	WHEN r.status = 27 THEN "全身無制限プラン"
	WHEN r.status = 28 THEN "平日とく得10回プラン"
	WHEN r.status = 29 THEN "平日とく得15回プラン"
	WHEN r.status = 30 THEN "平日とく得無制限プラン"
    WHEN r.status = 90 THEN "脱毛最終仕上げプラン"
    WHEN r.status = 101 THEN "エステ契約60分"
    WHEN r.status = 102 THEN "エステ契約90分"
    WHEN r.status = 103 THEN "整体契約"
END AS "予約ステータス"
From
  reservation r Left join customer c On r.customer_id = c.id
  Left join shop s On r.shop_id = s.id
  Left join course o On r.course_id = o.id
  Left join staff t On r.tstaff_id = t.id
Where
  r.del_flg = 0
  And r.type = 2 
  ANd c.id NOT IN (185858, 184122, 93933)
  AND SUBSTRING( r.hope_date, 1, 7 ) = "'.$date1.'"
';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("予約番号,来店日,会員番号,会員氏名,店舗名,コース名,スタッフ名,スタッフ番号,施術時間（分）,請求番号,予約ステータス\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['予約番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['来店日'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['会員氏名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コース名'],"SJIS-win", "UTF-8"). ",";
    		echo mb_convert_encoding($data['スタッフ名'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['スタッフ番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['施術時間'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['請求番号'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['予約ステータス'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

