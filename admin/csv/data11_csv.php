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
SELECT c.id '顧客ID', c.no '会員番号', c.name '名前', c.name_kana '名前カナ', h.name '登録店舗' ,
CASE
 WHEN t.status=0 THEN '契約中'
 WHEN t.status=1 THEN '契約終了'
 WHEN t.status=2 THEN 'クーリングオフ'
 WHEN t.status=3 THEN '中途解約'
 WHEN t.status=4 THEN 'プラン変更'
 WHEN t.status=5 THEN 'ローン取消'
 WHEN t.status=6 THEN '自動解約'
 WHEN t.status=7 THEN '契約待ち'
 WHEN t.status=8 THEN '返金保証回数終了'
END '契約状況',s.pay_date '支払日'
FROM sales AS s,customer AS c, shop AS h,contract AS t
WHERE c.id = s.customer_id
AND c.id = t.customer_id
AND t.del_flg=0
AND c.del_flg =0
AND s.del_flg =0
AND c.card_name = ''
AND (s.option_card <>0 OR s.payment_card<>0)
AND s.chk_flg =0
AND h.id=s.shop_id
AND s.pay_date BETWEEN '".$date1."' AND '".$date2."'
GROUP BY s.customer_id
ORDER BY s.pay_date
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,会員番号,名前,名前カナ,登録店舗\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['名前カナ'],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($data['登録店舗'],"SJIS-win", "UTF-8") . ",";
		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

