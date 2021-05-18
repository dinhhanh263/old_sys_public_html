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

$table = "contract";
// 詳細を取得----------------------------------------------------------------------------
if( $_POST['contract_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE (status=0 or status=5) and del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	$data = Get_Table_Row($table, " WHERE del_flg=0 and id = '" . addslashes($_POST['id']) . "'");
	$customer = Get_Table_Row("customer", " WHERE del_flg=0 and id = '" . addslashes($data['customer_id']) . "'");
	//if(!$_POST['loan_date'] || $_POST['loan_date']>date('Y-m-d') || $data['loan_date']<>"0000-00-00" && $data['loan_date']<date('Y-m-d') && $data['loan_status']==1 ){
	if(!$_POST['loan_date'] || $_POST['loan_date']>date('Y-m-d')  ){
		$gMsg  = "※ローン処理日を入力してください。";
		if($_POST['loan_date']>date('Y-m-d')  )$gMsg = "※未来日にローン処理できません。";
		//$gMsg .= "<font color='red' size='-1'>※過去変処理できません。</font>";

		if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsg ); //ローン一覧へ
		else header( "Location: /admin/contract/index.php?customer_id=".$customer['id']."&gMsg=".$gMsg ); 				//予約詳細へ
	}else{
		$_POST['edit_date'] = date('Y-m-d H:i:s');
		$field = array("pay_complete_date","loan_status","loan_date" , "edit_date");

		if($_POST['loan_status']==1 && $data['balance']<=0){
			$_POST['pay_complete_date'] = $_POST['loan_date'];
		}else $_POST['pay_complete_date'] = "0000-00-00";

		$data_ID = Update_Data("contract",$field,$_POST['id']);

		if( $data_ID ) 	{
			header("Location: /admin/contract/index.php?customer_id=" . $customer['id']);
			if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsgv ); //ローン一覧へ
			else header("Location: /admin/contract/index.php?customer_id=".$customer['id']); 				//契約一覧へ
		}else {
			$gMsg = 'エラーが発生しました。';
			if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsg ); //ローン一覧へ
			else header( "Location: /admin/contract/index.php?customer_id=".$customer['id']."&gMsg=".$gMsg ); 				//契約一覧へ

		}
	}
}

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//courseリスト
$course_list  = getDatalist("course");

?>
