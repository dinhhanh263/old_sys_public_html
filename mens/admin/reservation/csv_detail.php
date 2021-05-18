<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

$table = "reservation";

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}
//ccstaff list
$ccstaff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 AND shop_id=999 ORDER BY id" );
$ccstaff_list[0] = "-";
while ( $result1 = $ccstaff_sql->fetch_assoc() ) {
	$ccstaff_list[$result1['id']] = $result1['name'];
}

// $mensdb = changedb();

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}


// 詳細を取得-----------------------------------------------------------------------------
if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}
elseif( $_POST['customer_id'] != "" )  {
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
	if(!$_POST['type'] && !$_POST['new_flg']) $_POST['type'] = 2;			//次回予約新規時、区分を施術に暫定
	if(!$_POST['room_id'] && !$_POST['new_flg']) $_POST['room_id'] = 31;	//次回予約新規時、施術ルーム１に暫定
	if( $_POST['shop_id'] != "" )$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($_POST['shop_id'])."'");
	
}
elseif( $_POST['shop_id'] != "" ){
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($_POST['shop_id'])."'");
}elseif($authority_shop)$shop = $authority_shop;
else $shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = 1");

//契約詳細取得-----------------------------------------------------------------------------
if($_POST['hope_date'])$where_contract = " and (contract_date <= '".$_POST['hope_date']."' or contract_date='0000-00-00')";
if($data['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($data['contract_id'])."'");
elseif($_POST['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
//else $contract = Get_Table_Row("contract"," WHERE customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' and status=0 and end_date>='".date("Y-m-d")."'");
elseif(!$_POST['new_flg'] && !$data['new_flg']) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' and del_flg=0 and (status=0 or status=5 or status=6 or status=7) ".$where_contract." order by id desc"); // 契約中コース指定status=0,5

//新契約詳細取得-----------------------------------------------------------------------------
if($contract['new_contract_id'] != 0 ) $new_contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($contract['new_contract_id'])."'");


//csv export----------------------------------------------------------------------
$filename = "reservation_detail.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

echo mb_convert_encoding("会員番号,名前,名前カナ,区分,店舗,契約コース,来店状況,予約日程,予約時間,所要時間,施術主担当,施術サブ担当1,施術サブ担当2,契約状況\n","SJIS-win", "UTF-8");

echo $customer['no']  . ","; // 会員番号
echo mb_convert_encoding($customer['name'],"SJIS-win", "UTF-8")  . ",";
echo mb_convert_encoding($customer['name_kana'],"SJIS-win", "UTF-8")  . ",";
echo mb_convert_encoding($gResType4[$data['type']],"SJIS-win", "UTF-8")  . ","; // 区分
echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ","; // 店舗
echo mb_convert_encoding($course_list[$contract['course_id']],"SJIS-win", "UTF-8")  . ","; // 契約コース
echo mb_convert_encoding($gBookStatus[$data['status']],"SJIS-win", "UTF-8")  . ","; // 来店状況
echo $data['hope_date'] . ","; // 予約日程
echo $gTime2[$data['hope_time']]. ","; // 予約時間
echo mb_convert_encoding($gLength[$data['length']],"SJIS-win", "UTF-8"). ","; // 所要時間
echo mb_convert_encoding($staff_list[$data['tstaff_id']],"SJIS-win", "UTF-8"). ","; // 施術主担当
echo mb_convert_encoding($staff_list[$data['tstaff_sub1_id']],"SJIS-win", "UTF-8"). ","; // 施術サブ担当1
echo mb_convert_encoding($staff_list[$data['tstaff_sub2_id']],"SJIS-win", "UTF-8"). ","; // 施術サブ担当2
echo mb_convert_encoding(($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]),"SJIS-win", "UTF-8"). ","; // 契約状況
echo "\n";
//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
?>
