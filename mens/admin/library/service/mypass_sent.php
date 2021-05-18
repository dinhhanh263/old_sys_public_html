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

$table = "customer";

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['id'] != "" ) $data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	if(!$_POST['pw_sent_date'] || $_POST['pw_sent_date']>date('Y-m-d') || $_POST['pw_sent_date']=="0000-00-00" ){
		$gMsg  = "<font color='red' size='-1'>※発行日を入力してください。</font>";
		$gMsg .= "<font color='red' size='-1'>※未来日に処理できません。</font>";
		header( "Location: ../reservation/edit.php?id=".$_POST['reservation_id']."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']."&gMsg=".$_POST['hope_date'] );
	}else{

		//顧客tableに反映---------------------------------------------------------------------------
		if(!$_POST['pw_sent_flg']) $_POST['pw_sent_flg'] =0;
		$_POST['edit_date'] = date('Y-m-d H:i:s'); // アイパスを変更したら変更日時を入れる 20151102 shimada
		$field = array('pw_sent_flg',"pw_sent_date","edit_date");
		$data_ID = Update_Data($table,$field,$_POST['id']);
	//var_dump($_POST);exit;
		if( $data_ID ) 	{
			header( "Location: ../reservation/edit.php?id=".$_POST['reservation_id']."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date'] ); //予約詳細へ
		}else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}

?>
