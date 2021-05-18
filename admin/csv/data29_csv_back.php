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
$dSql = 'SELECT 
  c.id,
  c.status,
  c.status "契約状況",
  c.shop_id "契約店舗ID",
  s.name "契約店舗名",
  c.customer_id,
  u.no "会員番号",
  c.staff_id "スタッフID",
  t.name "スタッフ名",
  t.code "スタッフ番号",
  t.shop_id "所属店舗ID",
  s.name "所属店舗名",
  c.course_id "コースID",
  o.name "コース名",
  o.type,
  o.times,
  o.zero_flg,
  o.new_flg,
  c.old_course_id "旧コースID",
  o2.name "旧コース名",
  o2.type type2,
  o2.times times2,
  o2.zero_flg zero_flg2,
  o2.new_flg new_flg2,  
  c.price "金額",
  c.contract_date "契約日",
  c.cancel_date "解約日",
  c.pay_complete_date "支払完了日",
  c.conversion_flg
FROM 
  contract c left join shop s on c.shop_id = s.id
  left join customer u on c.customer_id = u.id
  left join staff t on c.staff_id = t.id
  left join course o on c.course_id = o.id
  left join course o2 on c.old_course_id = o2.id
WHERE
  c.del_flg = 0
  AND c.old_course_id <> 0
  AND SUBSTRING( c.pay_complete_date, 1, 7 ) =  "'.$date1.'"
';

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "csv_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);


if ( $dRtn3->num_rows >= 1 ) {
  echo mb_convert_encoding("id,status,契約状況,契約店舗ID,契約店舗名,customer_id,会員番号,スタッフID,スタッフ名,スタッフ番号,所属店舗ID,所属店舗名,コースID,コース名,type,times,zero_flg,new_flg,旧コースID,旧コース名,type2,times2,zero_flg2,new_flg2,金額,契約日,解約日,支払完了日,conversion_flg\n","SJIS-win", "UTF-8");

  while ( $data = $dRtn3->fetch_assoc() ) {
    echo mb_convert_encoding($data['id'],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($data['status'],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($gContractStatus[$data['契約状況']],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($data['契約店舗ID'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['契約店舗名'],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($data['customer_id'],"SJIS-win", "UTF-8")  . ",";
    echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8") . ","; 
    echo mb_convert_encoding($data['スタッフID'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['スタッフ名'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['スタッフ番号'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['所属店舗ID'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['所属店舗名'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['コースID'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['コース名'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['type'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['times'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['zero_flg'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['new_flg'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['旧コースID'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['旧コース名'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['type2'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['times2'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['zero_flg2'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['new_flg2'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['金額'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['解約日'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['支払完了日'],"SJIS-win", "UTF-8"). ",";
    echo mb_convert_encoding($data['conversion_flg'],"SJIS-win", "UTF-8"). ",";
    echo "\n";
  }

  // CSV Export Log
  // setCSVExportLog($_POST['csv_pw'],$filename);
}
