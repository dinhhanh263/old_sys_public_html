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
SELECT
  c.name as '契約者氏名',
  c.name_kana as '名前カナ',
  c.no as '会員番号',
  s2.pay_date as 'プラン変更手続き日',
  t2.start_ym as '新月額施術開始年月',
  CASE
    WHEN (SELECT COUNT(r1.type) FROM reservation as r1
            WHERE
              r1.customer_id=t1.customer_id AND
              r1.type = 2 AND
              r1.del_flg = 0) >0 THEN (SELECT r2.hope_date FROM reservation as r2
                                        WHERE
                                          r2.customer_id=t1.customer_id AND
                                          r2.type = 2 AND
                                          r2.del_flg = 0
                                          ORDER BY r2.hope_date DESC limit 1)
    ELSE 'トリートメント予約がありません。'
    END
   as '最新トリートメント予約',
  CASE t1.pay_type
    WHEN '2' THEN 'クレジット引落'
    WHEN '3' THEN '銀行引落'
    ELSE '未'
  END as '旧支払方法',
  CASE t2.pay_type
    WHEN '2' THEN 'クレジット引落'
    WHEN '3' THEN '銀行引落'
    ELSE '未'
  END as '新支払方法',
  u1.name as '旧プラン名',
  t1.r_times as '旧消化回数',
  CASE
    WHEN u1.type=0 THEN '-'
    WHEN (u1.type=1 AND t1.r_times>1) THEN
      (SELECT (s1.option_price + s1.option_transfer + s1.option_card) FROM sales as s1 WHERE s1.customer_id = c.id AND s1.type = 8 AND s1.del_flg=0 order by s1.pay_date DESC limit 1)
    WHEN (u1.type=1 AND c.introducer_type=3) THEN
      '社員割引。確認してください。'
    WHEN (c.introducer_type>1 OR c.partner > 0) THEN
      '企業または特別紹介。確認してください。'
    WHEN t2.pay_type < 1 THEN
     '引落手続き未完了。確認してください。'
    ELSE '引落履歴がありません。確認してください。'
  END as '旧プラン最終引落金額（予定含）',
  u2.name as '新プラン名',
  t2.fixed_price as '新プラン初回消化金額',
  (s2.payment_cash + s2.payment_card)
   as 'プラン変更画面レジ清算入金額',
  s2.balance as 'プラン変更後売掛金'
FROM contract as t1,contract as t2,sales as s2,customer as c,course as u1,course as u2
WHERE
  t1.customer_id = c.id AND
  t2.customer_id = c.id AND
  s2.customer_id = c.id AND
  t1.del_flg = 0 AND
  t2.del_flg = 0 AND
  s2.del_flg = 0 AND
  t1.status = 4 AND
  t2.status = 0 AND
  s2.type = 6 AND
  s2.id = t1.sales_id AND
  s2.course_id = t2.course_id AND
  t1.new_contract_id = t2.id AND
  t1.new_course_id = u2.id AND
  t2.old_course_id = u1.id AND
  u2.new_flg = 1  AND
  s2.reg_date BETWEEN '".$date1."' AND '".$date2."'

";


$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("契約者氏名,名前カナ,会員番号,プラン変更手続き日,新月額施術開始年月,最新トリートメント予約,旧支払方法,新支払方法,旧プラン名,旧消化回数,旧プラン最終引落金額（予定含）,新プラン名,新プラン初回消化金額,プラン変更画面レジ清算入金額,プラン変更後売掛金\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo mb_convert_encoding($data['契約者氏名'],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($data['名前カナ'],"SJIS-win", "UTF-8")  . ",";
		echo $data['会員番号'] . ",";
		echo $data['プラン変更手続き日']. ",";
		echo $data['新月額施術開始年月']. ",";
		echo mb_convert_encoding($data['最新トリートメント予約'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['旧支払方法'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['新支払方法'],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['旧プラン名'],"SJIS-win", "UTF-8") . ","; 
		echo $data['旧消化回数']. ",";
		echo mb_convert_encoding($data['旧プラン最終引落金額（予定含）'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($data['新プラン名'],"SJIS-win", "UTF-8"). ",";
		echo $data['新プラン初回消化金額']. ",";
		echo $data['プラン変更画面レジ清算入金額']. ",";
		echo $data['プラン変更後売掛金']. ",";
		echo "\n";
	}

	// CSV Export Log 
	// setCSVExportLog($_POST['csv_pw'],$filename);
}

