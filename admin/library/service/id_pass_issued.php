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

$table = "customer";

// 詳細を取得----------------------------------------------------------------------------
if( $_GET['customer_id'] != "" ) $data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_GET['customer_id'])."'");

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	$gMsg = validate();
	if (empty($gMsg)){
		//customerテーブルに反映---------------------------------------------------------------------------
		$_POST['edit_date'] = date('Y-m-d H:i:s'); // アイパスを変更したら変更日時を入れる 20151102 shimada
		$field = array('pw_sent_flg',"pw_sent_date","edit_date");
		$data_ID = Update_Data($table,$field,$_POST['id']);

		if( $data_ID ) {
			$gMsg = '（済）';
			header("Location: ../service/id_pass_issued.php?customer_id=" .$data['id'] );
		} else {
			$gMsg = "<font color='red' size='-1'>※エラーが発生しました、もう一度登録してください。</font>";
		}
	}
}
	function validate() {
		$gMsg ="";
		if(!$_POST['pw_sent_date'] || $_POST['pw_sent_date']=="0000-00-00"){
			$gMsg .= "<font color='red' size='-1'>※発行日を入力してください。</font>";
		}elseif($_POST['pw_sent_date']>date('Y-m-d')){
			$gMsg .= "<font color='red' size='-1'>※未来日に処理できません。</font>";
		}
		return $gMsg;
	}


?>
