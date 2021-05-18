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
SELECT t.cancel_date 'プラン変更日', h.name '店舗', t.customer_id '顧客ID', c.no '会員番号', c.name '名前', u.name '旧コース名', u2.name '新コース名',
 (t2.fixed_price - t2.discount) '新契約金額', t.r_times '旧コース消化済回数',
  (SELECT name FROM staff s1 WHERE s1.id = t.staff_id) '旧担当', 
  (SELECT name FROM staff s2 WHERE s2.id = t2.staff_id) '新担当', s.payment '支払金額'
FROM customer c, contract t
LEFT JOIN contract t2 ON t2.del_flg =0
AND t2.id = t.new_contract_id
LEFT JOIN course u2 ON u2.id = t.new_course_id, shop h, course u, sales s
WHERE c.id = t.customer_id
AND c.id = s.customer_id
AND c.ctype =1
AND t2.shop_id = h.id
AND t.course_id = u.id
AND s.pay_date >= t.cancel_date
AND s.payment >0
AND c.del_flg =0
AND t.del_flg =0
AND s.del_flg =0
AND t.status =4
AND u.type =1
AND u2.type=0
AND t.cancel_date BETWEEN '".$date1."' AND '".$date2."'
ORDER BY c.id, s.pay_date
";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("プラン変更日,店舗,顧客ID,会員番号,名前,旧コース名,新コース名,新契約金額,旧コース消化済回数,旧担当,新担当,支払金額\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['プラン変更日'],"SJIS-win", "UTF-8")  . ",";
    	echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo $data['顧客ID'] . ",";
		echo $data['会員番号']. ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['旧コース名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['新コース名'],"SJIS-win", "UTF-8") . ",";
		echo $data['新契約金額']. ",";
    	echo $data['旧コース消化済回数']. ",";
		echo mb_convert_encoding($data['旧担当'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['新担当'],"SJIS-win", "UTF-8"). ",";
    	echo $data['支払金額']. ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

