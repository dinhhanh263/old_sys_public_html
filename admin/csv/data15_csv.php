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
SELECT t.customer_id '顧客ID',h.name '店舗',
CASE
WHEN e.job =0 THEN  '-'
WHEN e.job =1 THEN  '会社員'
WHEN e.job =2 THEN  '公務員'
WHEN e.job =3 THEN  '学生'
WHEN e.job =4 THEN  'アルバイト'
WHEN e.job =5 THEN  '主婦'
WHEN e.job =6 THEN  '家事手伝い'
WHEN e.job =7 THEN  'ナイトワーク'
WHEN e.job =8 THEN  'その他'
WHEN e.job =9 THEN  '自営業'
WHEN e.job =10 THEN  '無職'
WHEN e.job =11 THEN  '契約社員'
ELSE e.job
END '職業',
CASE
WHEN e.birthday ='0000-00-00' THEN  '-'
ELSE
(YEAR(t.contract_date)-YEAR(e.birthday)) - (RIGHT(t.contract_date,5)<RIGHT(e.birthday,5))
END AS '年齢',
CASE
WHEN t.status =3 AND u.type=1 THEN  '月額退会'
WHEN t.status =3 AND u.type=0 THEN  '中途解約'
WHEN t.status =6 THEN  '自動解約'
WHEN t.status =1 THEN  '契約終了'
WHEN t.status =2 THEN  'クーリングオフ'
END '区分',
u.name 'コース',
CASE
WHEN u.type=0 THEN 'パック'
WHEN u.type=1 THEN '月額'
END 'コース区分',
 t.r_times '消化回数', t.contract_date '契約日', t.cancel_date '解約日'
FROM  `contract` t, shop h,sheet e,course u
WHERE t.shop_id = h.id
AND t.customer_id=e.customer_id
AND t.course_id=u.id
AND t.del_flg =0
AND t.status IN ( 3, 6, 1, 2 )
AND t.cancel_date BETWEEN '".$date1."' AND '".$date2."'
";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("顧客ID,店舗,職業,年齢,区分,コース,コース区分,消化回数,契約日,解約日\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['職業'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['年齢'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['区分'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['コース'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['コース区分'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['消化回数'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['解約日'],"SJIS-win", "UTF-8") . ","; 

		echo "\n";

	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

