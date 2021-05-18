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

$table = "contract";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("2014-02-28"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}


// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND  contract.contract_date>='".$_POST['contract_date']."'";
$dWhere .= " AND  contract.contract_date<='".$_POST['contract_date2']."'";

if($_POST['shop_id']) $dWhere .= " AND  contract.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND contract.status = '".addslashes($_POST['status'])."'";
if( $_POST['course_id'] ) $dWhere .= " AND contract.course_id = '".addslashes($_POST['course_id'])."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT contract.*,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel,customer.adcode as adcode FROM " . $table . ",customer WHERE contract.status=0 and contract.customer_id=customer.id and customer.del_flg=0 AND contract.del_flg = 0".$dWhere." ORDER BY contract.contract_date ";
//var_dump($dSql );
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);exit;
//csv export----------------------------------------------------------------------
$filename = "reservation_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("店舗,契約日,会員番号,名前,名前カナ,購入コース,請求金額,消化金額,役務残,消化回数,最終消化日,媒体,担当\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		if($data['times']) $price_once = round($data['price'] / $data['times'] , 0); // 金額/回
		$price_used =  $price_once * $data['r_times'] ; // 消化金額
		$price_remain = $data['price'] - $price_used;  // 役務残
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		if($data['adcode']) $ad_name = Get_Table_Col("adcode","name"," where id=".$data['adcode']);
		else $ad_name="";

		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['contract_date'] . ",";
		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['price']  . ",";
		echo $price_used  . ",";
		echo $price_remain  . ",";
		echo $data['r_times']  . ",";
		echo $latest_date  . ",";
		echo mb_convert_encoding($ad_name,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($staff_list[$data['staff_id']],"SJIS-win", "UTF-8")  . ",";
		
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
