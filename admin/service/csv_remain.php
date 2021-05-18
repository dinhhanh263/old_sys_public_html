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

$table = "reservation";

// 店舗リスト------------------------------------------------------------------------
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" );
$shop_list[0] = "全店舗";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_times[$result['id']] = $result['times'];
	$course_length[$result['id']] = $result['length'];
	$course_price[$result['id']] = $result['price'];
}

//------------------------------------------------------------------------------------
$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : ($_POST['hope_date2'] ? $_POST['hope_date2'] : date("Y-m-01"));
$_POST['hope_date2']=$_POST['hope_date2'] ? $_POST['hope_date2'] : ($_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['hope_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date2']." +1day"));

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['hope_date']."') ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}


$dWhere .= " AND  r.hope_date>='".$_POST['hope_date']."'";
$dWhere .= " AND  r.hope_date<='".$_POST['hope_date2']."'";
$order = "hope_date";

if($_POST['shop_id']) $dWhere .= " AND  r.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND r.status = '".addslashes($_POST['status'])."'";
if( $_POST['staff_id'] ) $dWhere .= " AND r.staff_id = '".addslashes($_POST['staff_id'])."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT r.id as reservation_id,r.customer_id,r.shop_id,r.hope_date,c.no as no,c.name as name,c.name_kana as name_kana,t.course_id,t.fixed_price,t.discount,t.latest_date,r.length,r.tstaff_id, (

SELECT h3.name
FROM staff s3, shop h3
WHERE s3.id = r.tstaff_id
AND s3.shop_id = h3.id
) '所属名' FROM " . $table . " r left join contract t on r.contract_id=t.id,customer c WHERE r.type=2 and r.status=11 and r.customer_id=c.id AND c.del_flg=0 AND r.del_flg = 0".$dWhere." ORDER BY r.".$order;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

// csv export----------------------------------------------------------------------
$filename = "remain_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("施術店舗,来店日,会員番号,名前,名前カナ,購入コース,請求金額,単価,所要時間(H),担当,所属名\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {
		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;

		// tax
		if($data['hope_date']<"2014-04-01"){
			$tax = 0.05;
			$tax2 = 1.05;
		}else{
			$tax_data = Get_Table_Row("basic"," WHERE id = 1");
			$tax =$tax_data['value'];
			$tax2 = 1+$tax_data['value'];
		}
		// 月額単価
		if($course_type[$data['course_id']] && $data['r_times']>$course_times[$data['course_id']]){
			//ホットペッパー月額ケース
			$price_once = $data['course_id']==70 ? $course_price['45']*$tax2/$course_times['45'] : $course_price[$data['course_id']]*$tax2/$course_times[$data['course_id']];
		}else{
			$price_once = round($data['price'] / $course_times[$data['course_id']] , 0);
		}
		//$price_once = round($data['price'] / $course_times[$data['course_id']] , 0);
		$price_used =  $price_once * $data['r_times'] ; // 消化（された）金額
		$length = $data['length']*0.5;
		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;  // 役務残,月額除外
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['hope_date'] . ",";
		echo $data['no']. ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8") . ","; 
		echo $data['price']. ",";
		echo $price_once. ",";
		echo $length. ",";
		//echo $price_remain. ",";
		//echo ($course_type[$data['course_id']] ? 0 : $data['r_times']). ",";
		echo mb_convert_encoding($staff_list[$data['tstaff_id']],"SJIS-win", "UTF-8") . ","; 
		echo mb_convert_encoding($data['所属名'],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	// CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
