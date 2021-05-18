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
$dSql = "SELECT
CASE
 WHEN t.conversion_flg=1 THEN 'プラン組替'
 ELSE 'プラン変更'
END '区分',t.cancel_date 'プラン変更日', h.name '販売店舗', t.customer_id '顧客ID', c.no '会員番号', c.name '名前', u.name '旧コース名', u2.name '新コース名', (
t2.fixed_price - t2.discount
) '新契約金額', t.r_times '旧コース消化済回数',t2.price '請求金額',t2.payment '支払済金額',t2.balance '売掛金',
CASE
 WHEN t2.pay_complete_date = '0000-00-00'  THEN ''
 ELSE t2.pay_complete_date
END '支払い完了日',
(
SELECT name
FROM staff s1
WHERE s1.id = t.staff_id
) '旧担当', (

SELECT name
FROM staff s2
WHERE s2.id = t2.staff_id
) '新担当', (

SELECT h3.name
FROM staff s3, shop h3
WHERE s3.id = t2.staff_id
AND s3.shop_id = h3.id
) '所属名'

FROM customer c, contract t
LEFT JOIN contract t2 ON t2.del_flg =0
AND t2.id = t.new_contract_id
LEFT JOIN course u2 ON u2.id = t.new_course_id, shop h, course u
WHERE c.id = t.customer_id
AND c.ctype =1
AND t2.shop_id = h.id
AND t.course_id = u.id
AND c.del_flg =0
AND t.del_flg =0
AND t.status =4
AND NOT(u.type=1 AND u.new_flg=0 AND u2.type=1 AND u2.new_flg=1)
AND NOT(u.type=0 AND u.new_flg=0 AND u2.type=1)
AND NOT( t.r_times < u.times AND u2.times < u.times )
AND NOT( t.r_times < u.times AND u2.times = u.times AND u.zero_flg=1 AND u2.zero_flg=0)
AND t2.status=0 
AND t.cancel_date BETWEEN '".$date1."' AND '".$date2."'
";
// t2.status=0  契約中のみデータを取得するように変更 2017/08/07 modify by shimada


$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("区分,プラン変更日,販売店舗,顧客ID,会員番号,名前,旧コース名,新コース名,新契約金額,旧コース消化済回数,請求金額,支払済金額,売掛金,支払い完了日,旧担当,新担当,所属名\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['区分'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['プラン変更日'],"SJIS-win", "UTF-8")  . ",";
    	echo mb_convert_encoding($data['販売店舗'],"SJIS-win", "UTF-8")  . ",";
		echo $data['顧客ID'] . ",";
		echo $data['会員番号']. ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['旧コース名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['新コース名'],"SJIS-win", "UTF-8") . ","; 
		echo $data['新契約金額']. ",";
    	echo $data['旧コース消化済回数']. ",";
    	echo $data['請求金額']. ",";
    	echo $data['支払済金額']. ",";
    	echo $data['売掛金']. ",";
    	echo $data['支払い完了日']. ",";
		echo mb_convert_encoding($data['旧担当'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['新担当'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['所属名'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

