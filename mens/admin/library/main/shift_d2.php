<?php
require_once LIB_DIR . 'db.php';

//SHOPリスト------------------------------------------------------------------------
$shop_list = array();
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 and (status=2 or id=999) ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $shop_result = $shop_sql->fetch_assoc() ) {
	$shop_list[$shop_result['id']] = $shop_result['name'];
}

if(!$_POST['shop_id']) $_POST['shop_id'] = 1010;
$table = "shift";


$shift_month = $_POST['hope_date'] ? substr($_POST['hope_date'],0,7) : date("Y-m");
$current_day = date("j",strtotime($_POST['hope_date'])); //月:先頭にゼロをつけない。
$selected_field = "day".$current_day;

// 検索条件の設定-------------------------------------------------------------------
$dWhere =" WHERE del_flg=0 ";
$shop_id = $_POST['shop_id']=="999" ? 999 : $_POST['shop_id']; //call center
if($_POST['shop_id']) $dWhere .= " AND shop_id='".$shop_id."'";
if($current_day) $dWhere .= " AND day".$current_day." in(1,2,3,6,8,9,10,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,51,52,53,54,55,56,101,102,103,104,105,106)";
if($shift_month) $dWhere .= " AND shift_month='".$shift_month."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY day".$current_day.",staff_id ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//スタッフリスト------------------------------------------------------------------------
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

$sql = $GLOBALS['mysqldb']->query( "select id,new_face from staff WHERE del_flg = 0  AND status=2 " ) or die('query error'.$GLOBALS['mysqldb']->error);
if($sql){
	while ( $result = $sql->fetch_assoc() ) {
		$staff_staus[$result['id']] = $result['new_face'];
	}
}

//休憩リスト------------------------------------------------------------------------
if($_POST['shop_id'] && $_POST['hope_date']){
	$sql = $GLOBALS['mysqldb']->query( "select * from rest WHERE del_flg = 0  AND shop_id= ".$_POST['shop_id'] ." AND hope_date= '".$_POST['hope_date']."'") or die('query error'.$GLOBALS['mysqldb']->error);
	if($sql){
		$j=90;
		while ( $result = $sql->fetch_assoc() ) {
			$staff_data[$result['staff_id']][$j]['id'] 	= $result['id'];
			$staff_data[$result['staff_id']][$j]['no'] 			= "休憩";
			$staff_data[$result['staff_id']][$j]['hope_time'] 	= $result['hope_time'];
			$staff_data[$result['staff_id']][$j]['length'] 		= $result['length'];
			$j++;
		}
	}
}

if ( $dRtn3->num_rows >= 1 ) {
	while ( $data_staff = $dRtn3->fetch_assoc() ) {
		if($gShiftType[$data_staff[$selected_field]]=="欠"){
			$color ="red";
		}elseif($staff_staus[$data_staff['staff_id']]){
			$color ="blue";
		}else{
			$color ="";
		}
		$html .="<font color=".$color.">".$staff_list[$data_staff['staff_id']] ."(". $gShiftType[$data_staff[$selected_field]].")</font>   &nbsp; ";
		$staff_current[$data_staff['staff_id']] = $staff_list[$data_staff['staff_id']];
		$staff_shift[$data_staff['staff_id']] = $gShiftType[$data_staff[$selected_field]];
	}
}

?>