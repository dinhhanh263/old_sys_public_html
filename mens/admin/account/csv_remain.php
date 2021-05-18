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

// $table = "sales";
$table = "r_times_history"; // 消化管理テーブル

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop("全店舗");

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" );
//$shop_list[0] = "全店舗";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//}

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_times[$result['id']] = $result['times'];
	$course_length[$result['id']] = $result['length'];
	$course_price[$result['id']] = $result['price'];
}

//------------------------------------------------------------------------------------
$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : ($_POST['pay_date2'] ? $_POST['pay_date2'] : date("Y-m-d"));
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date2']." +1day"));

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

$dWhere .= " AND  r_times_history.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  r_times_history.pay_date<='".$_POST['pay_date2']."'";
$order = "pay_date";

if($_POST['shop_id']) $dWhere .= " AND  r_times_history.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['type'] ) $dWhere .= " AND r_times_history.type = '".addslashes($_POST['type'])."'";

// 月額コースID取得
// $month_id = implodeArray("course","id"," where del_flg=0 and type=1");

// if( $_POST['course']==1 ) $dWhere .= " AND r_times_history.course_id in (".$month_id."  )";
// if( $_POST['course']==2 ) $dWhere .= " AND r_times_history.course_id not in (".$month_id."  )";
if( $_POST['course']==3 ) $dWhere .= " AND r_times_history.times >1 "; // 複数回コース  
if( $_POST['course']==4 ) $dWhere .= " AND r_times_history.times =1 "; // 1回コース
if( $_POST['staff_id'] ) $dWhere .= " AND r_times_history.staff_id = '".addslashes($_POST['staff_id'])."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT r_times_history.*,c.no as no,c.name as name,c.name_kana as name_kana,r.length FROM " . $table . "  left join reservation r on r_times_history.reservation_id=r.id,customer c WHERE r_times_history.r_times>0 and r_times_history.customer_id=c.id AND c.del_flg=0 AND c.ctype=1 AND r_times_history.del_flg = 0".$dWhere." ORDER BY r_times_history.".$order;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//csv export----------------------------------------------------------------------
$filename = "remain_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("区分,店舗,来店日,会員番号,名前,名前カナ,購入コース,請求金額,単価,所要時間(H),役務残(ﾊﾟｯｸ),消化済回数\n","SJIS-win", "UTF-8");
	
	while ( $data = $dRtn3->fetch_assoc() ) {
		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;
		/* 160601に本番反映
		// tax
		if($data['pay_date']<"2014-04-01"){
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
		} */
		 $price_once = round($data['price'] / $course_times[$data['course_id']] , 0);
		
		$price_used =  $price_once * $data['r_times'] ; // 消化（された）金額
		//$length = $course_length[$data['course_id']] * 0.5;
		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;  // 役務残,月額除外
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		if($data['type']==2) echo mb_convert_encoding($gResType3[$data['type']],"SJIS-win", "UTF-8")  . ",";
		else echo mb_convert_encoding(($data['rsv_status'] ? $gRsvStatus[$data['rsv_status']] : $gResType3[$data['type']]),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['pay_date'] . ",";
		echo $data['no']. ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8") . ","; 
		echo $data['price']. ",";
		echo $price_once. ",";
		echo ($data['length']*0.5). ",";
		echo $price_remain. ",";
		echo ($course_type[$data['course_id']] ? 0 : $data['r_times']). ",";
		echo "\n";
	}

	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
