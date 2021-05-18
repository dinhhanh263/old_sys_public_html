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
SELECT t.cancel_date '解約日', h.name '契約店舗', t.customer_id '顧客ID', c.no '会員番号', c.name '名前', u.name 'コース名', 
 (t.fixed_price - t.discount) '契約金額',
 s1.payment_loan 'ローン金額', 
 t.r_times '消化済回数',
  (SELECT name FROM staff f1 WHERE f1.id = t.staff_id) '契約担当', 
  (SELECT name FROM shop h1 WHERE h1.id = s.shop_id) '解約店舗', 
 s.payment_loan '既払金', (s.payment_transfer+s.option_transfer) '返金金額'
FROM customer c, contract t
LEFT JOIN sales s1 ON (s1.del_flg=0 AND t.customer_id = s1.customer_id AND s1.type<>5 AND s1.payment_loan>0 AND s1.pay_date>=t.contract_date AND s1.pay_date<t.cancel_date)
, shop h, course u, sales s
WHERE c.id = t.customer_id
AND c.id = s.customer_id
AND c.ctype =1
AND t.shop_id = h.id
AND t.course_id = u.id
AND s.id = t.sales_id
AND c.del_flg =0
AND t.del_flg =0
AND s.del_flg =0
AND t.status =3
AND t.payment_loan >0
AND s.payment_loan >0
AND t.cancel_date BETWEEN '".$date1."' AND '".$date2."'
ORDER BY c.id, s.pay_date
";


$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("解約日,契約店舗,顧客ID,会員番号,名前,コース名,契約金額,ローン金額,消化済回数,契約担当,解約店舗,既払金,返金金額\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['解約日'],"SJIS-win", "UTF-8")  . ",";
    	echo mb_convert_encoding($data['契約店舗'],"SJIS-win", "UTF-8")  . ",";
		echo $data['顧客ID'] . ",";
		echo $data['会員番号']. ",";
		echo mb_convert_encoding($data['名前'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コース名'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約金額'],"SJIS-win", "UTF-8") . ","; 
		echo $data['ローン金額']. ",";
    	echo $data['消化済回数']. ",";
		echo mb_convert_encoding($data['契約担当'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['解約店舗'],"SJIS-win", "UTF-8"). ",";
		echo $data['既払金']. ",";
    	echo $data['返金金額']. ",";
		echo "\n";
	}

	// CSV Export Log
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

