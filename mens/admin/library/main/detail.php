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


// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	// 空き確認.他の予約との重なり、21時前に終了できるか
	$sql = " WHERE id<>".$_POST['id'] . " AND hope_date='".addslashes($_POST['hope_date'])."' AND shop_id=".$_POST['shop_id']." AND room_id=".$_POST['room_id'];
	$sql .= " AND (hope_time<".$_POST['hope_time'] ." AND hope_time+length>".$_POST['hope_time'] ;//予約開始時間と比較(重なりあり)
	$sql .= " OR hope_time<".($_POST['hope_time']+$_POST['length']) ." AND hope_time+length>".($_POST['hope_time']+$_POST['length']) ;//予約終了時間と比較(重なりあり)
	$sql .= " OR hope_time>=".$_POST['hope_time'] ." AND hope_time+length<=".($_POST['hope_time']+$_POST['length']) . ")";//中にあり
	
	// 終了確認
	if( Get_Table_Row($table,$sql)){
		$gMsg = "<font color='red' size='-1'>※他の予約との重なりがあります。予約変更ができませんでした。</font>";
	}elseif(($_POST['hope_time']+$_POST['length'])>21){
		$gMsg = "<font color='red' size='-1'>※終了時間がオーバーします。予約変更ができませんでした。</font>";
	}else{
		$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
		if( $data_ID ) 	header( "Location: ./detail.php?id={$data_ID}");
		else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}

// 詳細を取得------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
}

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();


// courseリスト
$course_list  = getDatalistMens("course");

// room_list
if($data['hope_date']=="2014-02-28") $counseling_rooms = $CounselingRoomsMax;
elseif($data['hope_date']>="2014-03-01" && $data['hope_date']<="2014-03-03") $counseling_rooms = 6;
else $counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;

$medical_rooms = $shop['medical_rooms'] ? $shop['medical_rooms'] : $MedicalRooms;
$vip_rooms = $shop['vip_rooms'] ? $shop['vip_rooms'] : $VIPRooms;

for ($i = 1; $i <= $counseling_rooms; $i++) {
	$no = "1".$i;
	$room_list[$no] = $CounselingRoomName.$i;
}
for ($i = 1; $i <= $vip_rooms; $i++) {
	$no = "2".$i;
	$room_list[$no] = $VIPRoomName.$i;
}
for ($i = 1; $i <= $medical_rooms; $i++) {
	$no = "3".$i;
	$room_list[$no] = $MedicalRoomName.$i;
}

?>
