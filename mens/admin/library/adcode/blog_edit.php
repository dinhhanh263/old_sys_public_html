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

$table = "blog";
unset($_POST['shop_id']);

//画像の取り消し---------------------------------------------------------------------
if( $_POST['mode'] == "delete_image" && $_POST['id'] && $_POST['image_type']){
	$sql = "UPDATE ".$table." SET ".$_POST['image_type']."=''" . " WHERE id = " . addslashes($_POST['id']);
	$rtn = $GLOBALS['mysqldb']->query($sql);
	$_POST['mode'] = "";
}

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	//画像アップ
	if( $_FILES['img_file']['size']<>0 ){
		/*foreach($_FILES as $key => $val){
			if($_FILES[$key]['size']<>0){
				$target_name = 'Blog'.$_POST['id'].'_'.date("YmdHis");
				$_POST[$key] = Upload_File( $_FILES[$key]['name'] , $_FILES[$key]['tmp_name'] , IMG_UPLOAD_DIR , $target_name );
			}
		}*/

		$tmp_name = Upload_Img( $_FILES['img_file']['name'] , $_FILES['img_file']['tmp_name'] , IMG_UPLOAD_DIR , "tmp" );
		$pos = strrpos($tmp_name,".");//拡張子取得
		$ext = substr($tmp_name,$pos+1,strlen($tmp_name)-$pos);
		$ext = strtolower($ext);//小文字化
		$target_name    = 'blog'.date("YmdHis").".".$ext;
		$target_name_thumb = 'thumb_'.$target_name;
		$_POST['img_name'] = $target_name;
		Resize_Image(IMG_UPLOAD_DIR.$tmp_name,IMG_UPLOAD_DIR.$target_name,"100","100");
		Resize_Image(IMG_UPLOAD_DIR.$tmp_name,IMG_UPLOAD_DIR.$target_name_thumb,"50","50");
		

	}
	if($_POST['id'] != ""){
		$_POST['edit_date'] = date('Y-m-d H:i:s');
		$data_ID = Input_Update_Data($table);
	}else{
		$_POST['reg_date'] = date('Y-m-d H:i:s');
		$data_ID = Input_Data($table);
	}
	//$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	if( $data_ID ) 	header( "Location: blog.php");
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>
