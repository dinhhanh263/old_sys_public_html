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

$table = "karte";
$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");

// 来店なしデータ除外
$pre_reservation  = Get_Table_Col("reservation","id"," where customer_id=".$reservation['customer_id'] ." and type=2 and hope_date<'".$reservation['hope_date']."' order by hope_date desc limit 1");
$next_reservation = Get_Table_Col("reservation","id"," where customer_id=".$reservation['customer_id'] ." and type=2 and hope_date>'".$reservation['hope_date']."' order by hope_date  limit 1");

// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	
	// 顧客編集
	if($_POST['skin_status']) 	$_POST['skin_status']  = implode(",", $_POST['skin_status']);
	if($_POST['foot']) 			$_POST['foot'] 		   = implode(",", $_POST['foot']);
	if($_POST['finger']) 		$_POST['finger'] 	   = implode(",", $_POST['finger']);
	if($_POST['skin']) 			$_POST['skin'] 		   = implode(",", $_POST['skin']);

	// V(状態_v1)
	if($_POST['vio_v_stat']){
		$_POST['vio_v_stat'] = implode(",", $_POST['vio_v_stat']);
	} else {
		$_POST['vio_v_stat'] = "";
	}
	// I(状態_v1)
	if($_POST['vio_i_stat']){ 	
		$_POST['vio_i_stat']   = implode(",", $_POST['vio_i_stat']);
	} else {
		$_POST['vio_i_stat'] = "";
	}
	// O(状態_v1)
	if($_POST['vio_o_stat']){
	 	$_POST['vio_o_stat']   = implode(",", $_POST['vio_o_stat']);
	} else {
		$_POST['vio_o_stat'] = "";
	}
	// 脚(状態_v1)
	if($_POST['foot_stat']){ 	
		$_POST['foot_stat']    = implode(",", $_POST['foot_stat']);
	} else {
		$_POST['foot_stat'] = "";
	}
	// 腕(状態_v1)
	if($_POST['arm_stat']){
	 	$_POST['arm_stat']     = implode(",", $_POST['arm_stat']);
	} else {	 	
		$_POST['arm_stat'] = "";
	}
	// 背中(状態_v1)
	if($_POST['back_stat']){
		$_POST['back_stat']    = implode(",", $_POST['back_stat']);
	} else {	 	
		$_POST['back_stat'] = "";
	}
	// お腹、胸(状態_v1)
	if($_POST['stomach_stat']){ 	
		$_POST['stomach_stat'] = implode(",", $_POST['stomach_stat']);
	} else {	 	
		$_POST['stomach_stat'] = "";
	}
	// ヒップ(状態_v1)
	if($_POST['buttocks_stat']){ 	
		$_POST['buttocks_stat'] = implode(",", $_POST['buttocks_stat']);
	} else {	 	
		$_POST['buttocks_stat'] = "";
	}

	if($_POST['id'] != "" ){
		$_POST['edit_date'] = date("Y-m-d H:i:s");
		$data_ID = Input_Update_Data($table);
	}else{
		// 顧客新規
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		Input_Data($table);
	}
	if( $data_ID ) 	$gMsg = '記入が完了しました。';
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得
if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id = '".addslashes($data['reservation_id'])."'");
}elseif($_POST['reservation_id'] != "" )  {
	$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
	$data = Get_Table_Row($table," WHERE del_flg=0 and reservation_id = '".addslashes($_POST['reservation_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($reservation['customer_id'])."'");
}

if($pre_reservation) $pre_data = Get_Table_Row($table," WHERE del_flg=0 and reservation_id = '".addslashes($pre_reservation)."'");

// 店舗リスト------------------------------------------------------------------------

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
	$shop_code[$result['id']] = $result['code'];
}

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_list  = getDatalist("course");
?>
