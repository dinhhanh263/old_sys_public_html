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

$table = "karte";
$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");

// 今回施術したコースIDを登録用データに入れる
if($reservation['multiple_contract_id'])$select_contract_array = Get_Table_Array("contract","*", " WHERE del_flg=0 and id in (".addslashes($reservation['multiple_contract_id']).")");
foreach ($select_contract_array as $key => $value) {
	$multiple_course_id[] = $value['course_id'];
}
if($multiple_course_id)$_POST['multiple_course_id'] = implode(',', $multiple_course_id);

// 来店なしデータ除外
$pre_reservation  = Get_Table_Col("reservation","id"," where customer_id='".$reservation['customer_id'] ."' and type=2 and id<'".$reservation['id']."' order by hope_date desc limit 1");
$next_reservation = Get_Table_Col("reservation","id"," where customer_id='".$reservation['customer_id'] ."' and type=2 and id>'".$reservation['id']."' order by hope_date  limit 1");

// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	
	// 顧客編集
	if($_POST['skin_status']) 	$_POST['skin_status']  = implode(",", $_POST['skin_status']);
	if($_POST['foot']) 			$_POST['foot'] 		   = implode(",", $_POST['foot']);
	if($_POST['finger']) 		$_POST['finger'] 	   = implode(",", $_POST['finger']);
	if($_POST['skin']) 			$_POST['skin'] 		   = implode(",", $_POST['skin']);

	// 施術パーツ ・ 肌状態・形
	// 顔
	if($_POST['face_parts']){
		$_POST['face_parts'] = implode(",", $_POST['face_parts']);
	} else {
		$_POST['face_parts'] = "";
	}
	if($_POST['face_stat']){
		$_POST['face_stat'] = implode(",", $_POST['face_stat']);
	} else {
		$_POST['face_stat'] = "";
	}

	// うなじ
	if($_POST['neck_parts']){
		$_POST['neck_parts'] = implode(",", $_POST['neck_parts']);
	} else {
		$_POST['neck_parts'] = "";
	}

	// お腹・胸
	if($_POST['stomach_parts']){
		$_POST['stomach_parts'] = implode(",", $_POST['stomach_parts']);
	} else {
		$_POST['stomach_parts'] = "";
	}
	if($_POST['stomach_stat']){ 	
		$_POST['stomach_stat'] = implode(",", $_POST['stomach_stat']);
	} else {	 	
		$_POST['stomach_stat'] = "";
	}

	// 背中
	if($_POST['back_parts']){
		$_POST['back_parts'] = implode(",", $_POST['back_parts']);
	} else {
		$_POST['back_parts'] = "";
	}
	if($_POST['back_stat']){
		$_POST['back_stat']    = implode(",", $_POST['back_stat']);
	} else {	 	
		$_POST['back_stat'] = "";
	}

	// 腕
	if($_POST['arm_parts']){
		$_POST['arm_parts'] = implode(",", $_POST['arm_parts']);
	} else {
		$_POST['arm_parts'] = "";
	}
	if($_POST['arm_stat']){
	 	$_POST['arm_stat']     = implode(",", $_POST['arm_stat']);
	} else {	 	
		$_POST['arm_stat'] = "";
	}

	// ヒップ
	if($_POST['buttocks_parts']){
		$_POST['buttocks_parts'] = implode(",", $_POST['buttocks_parts']);
	} else {
		$_POST['buttocks_parts'] = "";
	}
	if($_POST['buttocks_stat']){
	 	$_POST['buttocks_stat']  = implode(",", $_POST['buttocks_stat']);
	} else {	 	
		$_POST['buttocks_stat'] = "";
	}

	// 脚
	if($_POST['foot_parts']){
		$_POST['foot_parts'] = implode(",", $_POST['foot_parts']);
	} else {
		$_POST['foot_parts'] = "";
	}
	if($_POST['foot_stat']){ 	
		$_POST['foot_stat']   = implode(",", $_POST['foot_stat']);
	} else {
		$_POST['foot_stat'] = "";
	}

	// VIO
	if($_POST['vio_parts']){
		$_POST['vio_parts'] = implode(",", $_POST['vio_parts']);
	} else {
		$_POST['vio_parts'] = "";
	}
	if($_POST['vio_v_stat']){
		$_POST['vio_v_stat'] = implode(",", $_POST['vio_v_stat']);
	} else {
		$_POST['vio_v_stat'] = "";
	}
	if($_POST['vio_i_stat']){ 	
		$_POST['vio_i_stat']   = implode(",", $_POST['vio_i_stat']);
	} else {
		$_POST['vio_i_stat'] = "";
	}
	if($_POST['vio_o_stat']){
	 	$_POST['vio_o_stat']   = implode(",", $_POST['vio_o_stat']);
	} else {
		$_POST['vio_o_stat'] = "";
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
	// 今回予約した契約情報
	if($reservation['multiple_contract_id'])$select_contract_array = Get_Table_Array("contract","*", " WHERE del_flg=0 and id in (".addslashes($reservation['multiple_contract_id']).")");

}

if($pre_reservation) $pre_data = Get_Table_Row($table," WHERE del_flg=0 and reservation_id = '".addslashes($pre_reservation)."'");

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
//$shop_list[0] = "-";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//	$shop_code[$result['id']] = $result['code'];
//}

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_list  = getDatalistMens("course");
?>
