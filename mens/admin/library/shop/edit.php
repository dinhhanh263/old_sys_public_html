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
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

$table = "shop";

unset($_POST['shop_id']);
//画像の取り消し-------------------------------------------------------------------------------------------
if( $_POST['mode'] == "delete_image" && $_POST['id'] && $_POST['image_type']){
	$sql = "UPDATE ".$table." SET ".$_POST['image_type']."=''" . " WHERE id = " . addslashes($_POST['id']);
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	$_POST['mode'] = "";
}
//編集-------------------------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	//動画、画像アップ
	if( !empty($_FILES) ){
		foreach($_FILES as $key => $val){
			if($_FILES[$key]['size']<>0){
				$target_name = 'Shop'.$_POST['id'].'_'.date("YmdHis");
				$_POST[$key] = Upload_File( $_FILES[$key]['name'] , $_FILES[$key]['tmp_name'] , IMG_SHOP_UPLOAD_DIR , $target_name );
			}
		}
	}
	if(!$_POST['card']) $_POST['card'] = 0;
	if(!$_POST['park']) $_POST['park'] = 0;
	//予約表とテーブル上のルーム数が不一致。暫定。20151204
	//$_POST['current_c_rooms'] = $_POST['counseling_rooms'];
	//$_POST['current_m_rooms'] = $_POST['medical_rooms'];
	if($_POST['id'] != ""){
		$_POST['edit_date'] = date("Y-m-d H:i:s");
		$data_ID = Input_Update_Data($table) ;
	}else{
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		$data_ID = Input_Data($table);
	}
	
	if( $data_ID ) 	header( "Location: ./");
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得-------------------------------------------------------------------------------------------
if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>
