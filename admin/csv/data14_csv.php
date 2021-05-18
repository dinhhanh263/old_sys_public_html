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
SELECT r.customer_id AS '顧客ID',c.no AS '会員番号',h.name AS '店舗',u.name AS '契約コース',r.hope_date AS '予約日程',r.persons AS '人数',
CASE r.route 
WHEN '1' THEN 'WEB'
WHEN '2' THEN '電話'
WHEN '3' THEN '飛込'
WHEN '4' THEN 'クーポンランド'
WHEN '5' THEN 'Mypage'
WHEN '6' THEN '店舗'
WHEN '7' THEN 'HotPepper'
WHEN '8' THEN 'チラシ'
WHEN '9' THEN 'TGA'
END AS '経由',
CASE r.status 
WHEN '0' THEN '-'
WHEN '11' THEN '来店'
WHEN '1' THEN '来店なし'
WHEN '2' THEN '未契約'
WHEN '3' THEN '契約月額'
WHEN '13' THEN '契約3回'
WHEN '4' THEN '契約6回'
WHEN '5' THEN '契約10回'
WHEN '6' THEN '契約12回'
WHEN '7' THEN '契約15回'
WHEN '8' THEN '契約18回'
WHEN '15' THEN '通いホーダイ'
WHEN '9' THEN 'カスタマイズ月額'
WHEN '10' THEN 'カスタマイズパック'
WHEN '14' THEN '体験'
END AS '来店状況',
CASE r.preday_status
WHEN '0' THEN '-'
WHEN '1' THEN '前日telOK'
WHEN '2' THEN '前日telﾙｽ'
WHEN '3' THEN '前日telNG'
WHEN '4' THEN 'お客様切電'
END AS '確認状況(前日tel)',
r.preday_cnt AS '前日架電回数',
CASE r.today_status
WHEN '0' THEN '-'
WHEN '1' THEN '予約時telOK'
WHEN '2' THEN '予約時telﾙｽ'
WHEN '3' THEN '予約時telNG'
WHEN '4' THEN 'お客様切電'
END AS '確認状況(予約時tel)',
r.today_cnt AS '予約時架電回数'
FROM customer AS c,reservation AS r LEFT JOIN course AS u ON r.course_id=u.id,shop AS h
WHERE r.del_flg = 0
AND c.del_flg=0 
AND c.id=r.customer_id
AND c.ctype=1
AND r.shop_id=h.id
AND r.rsv_status <> 13
AND r.type = 1
AND r.preday_status != 1 AND r.today_status != 1
AND r.hope_date BETWEEN '".$date1."' AND '".$date2."'

";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,会員番号,店舗,契約コース,予約日程,人数,経由,来店状況,確認状況(前日tel),前日架電回数,確認状況(予約時tel)\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約コース'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['予約日程'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['人数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['経由'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['来店状況'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['確認状況(前日tel)'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['前日架電回数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['確認状況(予約時tel)'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['予約時架電回数'],"SJIS-win", "UTF-8") . ","; 
		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

