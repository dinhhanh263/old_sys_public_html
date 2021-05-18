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

$table = "goal";
$ymd = str_replace("/", "-", $_POST['ym'])."-01";
$ym2 = date("Y/m", strtotime( $ymd."-2 month"));

// 詳細を取得-----------------------------------------------------------------------

if( $_POST['ym'] != "" ) $data = Get_Table_Row($table," WHERE del_flg=0 and ym2 = '".$ym2."'");

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	if(!$_POST['shop_id'] ){
		$gMsg = "※未来日にローン処理できません。";
		header( "Location: ./allowance.php?ym=".$_POST['ym']."&gMsg=".$gMsg );
	}else{
		if($_POST['shop_id'])  $_POST['shop_id'] = implode(",",$_POST['shop_id']);
		if($_POST['shop_id2'])$_POST['shop_id2'] = implode(",",$_POST['shop_id2']);
		if($_POST['shop_id3'])$_POST['shop_id3'] = implode(",",$_POST['shop_id3']);
		if($_POST['shop_id4'])$_POST['shop_id4'] = implode(",",$_POST['shop_id4']);
		if($_POST['shop_id5'])$_POST['shop_id5'] = implode(",",$_POST['shop_id5']);
		if($_POST['shop_id6'])$_POST['shop_id6'] = implode(",",$_POST['shop_id6']);
		if($_POST['shop_id7'])$_POST['shop_id7'] = implode(",",$_POST['shop_id7']);

		$_POST['reg_date'] = $_POST['edit_date'] = date('Y-m-d H:i:s');
		
		$field = array("ym2","shop_id","shop_id2","shops" );

		if($data['id']){
			array_push($field,  "edit_date");
			$data_ID = Update_Data($table,$field,$data['id']);
		}else{
			array_push($field,  "reg_date");
			$data_ID = Input_New_Data($table,$field);
		} 
		if( $data_ID ) 	{
			header( "Location: ./allowance.php?ym=".$_POST['ym']."&gMsg=".$gMsg );
		}else {
			$gMsg = 'エラーが発生しました。';
			header( "Location: ./allowance.php?ym=".$_POST['ym']."&gMsg=".$gMsg );
		}
	}
}

// 店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop("");
// $mensdb = changedb();

?>
