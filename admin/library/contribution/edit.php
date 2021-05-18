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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$gStatus = array( 0 => "非公開" , 1 => "公開"  );

$_POST['id'] = $_POST['id'] ? $_POST['id'] : $_GET['id'];
$table = "contribution";

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	if($_FILES['img_file']['size']!= 0) {
		$tmp_name = Upload_Img( $_FILES['img_file']['name'] , $_FILES['img_file']['tmp_name'] , IMG_UPLOAD_DIR."contribution/" , "tmp" );
		$pos = strrpos($tmp_name,".");//拡張子取得
		$ext = substr($tmp_name,$pos+1,strlen($tmp_name)-$pos);
		$ext = strtolower($ext);//小文字化
		$target_name    = 'contribution_'.date("YmdHis").".".$ext;
		$target_name_mo = 'contribution_mo_'.date("YmdHis").".".$ext;
		Resize_Image(IMG_UPLOAD_DIR."contribution/".$tmp_name,IMG_UPLOAD_DIR."contribution/".$target_name,"102","127");
		Resize_Image(IMG_UPLOAD_DIR."contribution/".$tmp_name,IMG_UPLOAD_DIR."contribution/".$target_name_mo,"70","87");
		$_POST['img_pc'] = $target_name;
		$_POST['img_mo'] = $target_name_mo;
	}
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	if( $data_ID ){
		$gMsg = '登録が完了しました。<br><br><b><a href="index.php">登録済みのリストへ</a></b>';
	}else{
		$gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}
// 詳細を取得----------------------------------------------------------------------------
if(  $_POST['id'] != "" ){
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
}
?>