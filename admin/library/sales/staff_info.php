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

$table = "allowance";

// 詳細を取得----------------------------------------------------------------------------

if( $_POST['ym'] != "" && $_POST['staff_id'] != "" ) $data = Get_Table_Row($table," WHERE del_flg=0 and staff_id=".$_POST['staff_id'] ." and ym = '".addslashes($_POST['ym'])."'");
if( $_POST['staff_id'] != "" ) $staff = Get_Table_Row("staff"," WHERE del_flg=0 and id=".$_POST['staff_id']);
$type = $data['type'] ? $data['type'] : $staff['type'] ;
$posi_salary =  Get_Table_Row("posi_salary"," WHERE del_flg=0 and position=".$type);

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	if(!$_POST['shop_id'] ){
		$gMsg = "※未来日にローン処理できません。";
		header( "Location: ./allowance.php?ym=".$_POST['ym']."&gMsg=".$gMsg );
	}else{
		if($_POST['shop_id'])  $_POST['shop_id'] = implode(",",$_POST['shop_id']);
		if($_POST['shop_id2'])$_POST['shop_id2'] = implode(",",$_POST['shop_id2']);
		$_POST['reg_date'] = $_POST['edit_date'] = date('Y-m-d H:i:s');
		
		$field = array("ym","shop_id","shop_id2" );

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
$shop_list = getDatalist4("shop");

?>
