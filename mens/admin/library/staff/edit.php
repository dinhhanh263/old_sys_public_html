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

$table = "staff";
//画像の取り消し
if( $_POST['mode'] == "delete_image" && $_POST['id'] && $_POST['image_type']){
	$sql = "UPDATE ".$table." SET ".$_POST['image_type']."=''" . " WHERE id = " . addslashes($_POST['id']);
	$rtn = $GLOBALS['mysqldb']->query($sql);
	$_POST['mode'] = "";
}
//編集-------------------------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	//画像アップ
	$page_param = '?start='.$_POST['start'].'&keyword='.$_POST['keyword'].'&shop_id='.$_POST['select_shop_id'].'&type='.$_POST['select_type'].'&class='.$_POST['select_class'].'&status='.$_POST['select_status'].'&line_max='.$_POST['line_max'];
	unset($_POST['start']);
	unset($_POST['keyword']);
	unset($_POST['select_shop_id']);
	unset($_POST['select_type']);
	unset($_POST['select_class']);
	unset($_POST['select_status']);
	unset($_POST['line_max']);

	//画像アップ
	if( !empty($_FILES) ){
		foreach($_FILES as $key => $val){
			if($_FILES[$key]['size']<>0){
				$target_name = 'staff'.$_POST['id'].'_'.date("YmdHis");
				$_POST[$key] = Upload_File( $_FILES[$key]['name'] , $_FILES[$key]['tmp_name'] , IMG_STAFF_UPLOAD_DIR , $target_name );
			}
		}
	}

	if(!$_POST['new_face']) $_POST['new_face'] = 0;
	if(!$_POST['treat_only']) $_POST['treat_only'] = 0;
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : Input_Data($table);
	if( $data_ID ) {
		$staff = Get_Table_Row($table," WHERE id = '".$data_ID."'");
		if($staff['auth_id']){
			//アカウント更新
			$GLOBALS['mysqldb']->query("update staff set auth_id='".$staff_auth['id']."',edit_date=NOW() where id='".$data_ID."'");
			$GLOBALS['mysqldb']->query("update authority set staff_id='".$data_ID."',authority='".$staff['type']."',login_id='".$staff['login_id']."',password='".$staff['password']."',edit_date=NOW() where id='".$staff['auth_id']."'");
		}else{
			//アカウント新規
			$GLOBALS['mysqldb']->query("insert authority set staff_id='".$data_ID."',authority='".$staff['type']."',login_id='".$staff['login_id']."',password='".$staff['password']."',reg_date=NOW(), edit_date=NOW()");
			$staff_auth = Get_Table_Row("authority"," WHERE staff_id = '".$data_ID."'");
			$GLOBALS['mysqldb']->query("update staff set auth_id='".$staff_auth['id']."',reg_date=NOW(),edit_date=NOW() where id='".$data_ID."'");
		}
		header( "Location: ./index.php{$page_param}");
	}
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得-------------------------------------------------------------------------------------------
if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

//店舗リスト-------------------------------------------------------------------------------------------
$shop_list = getDatalist4("shop");
?>
