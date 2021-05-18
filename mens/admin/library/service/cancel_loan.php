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

$table = "contract";

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	
  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));
  
  if($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }else{

	// データ取得-----------------------------------------------------------------------

	if($_POST['id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['id'] )."' ");
	else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'] )."' and (status=0 or status=2  or status=5)  or status=7");//いらない？

	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=9 and contract_id = '".addslashes($contract['id'])."'");

	// POST INPUT-------------------------------------------------------------------

	// loan cancel
	$_POST['status'] = 5; 
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

	//売掛金
	$_POST['balance'] = $contract['price']-( $contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer']); 

	$_POST['pay_date'] = $_POST['cancel_date'];
	$_POST['payment'] = $_POST['payment_loan'];

	// 売上tableに反映---------------------------------------------------------------

	// loan cancel
	$_POST['type'] = 9; 
	$_POST['contract_id'] = $contract['id'];
		
	$sales_field  = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_loan","balance","memo","pay_date","cancel_date","reg_date","edit_date");
	$sales_field2 = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_loan","balance","memo","pay_date","cancel_date","edit_date");


	// 更新 or 新規------------------------------------------------------------------

	// 再度精算、前回精算の取り消し
	if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	// ローン取消後のプラン変更と支払方法変更のため、旧契約データをコピー
	if($contract['status']==0){
		$contract['payment'] = $contract['payment'] - $contract['payment_loan'];
		$contract['balance'] = $contract['balance'] + $contract['payment_loan'];
		$contract['payment_loan'] = 0;

		// 契約待ち
		$contract['status'] = 7; 

		$new_contract_field  = array("status","reservation_id","sales_id","customer_id","shop_id","staff_id","course_id","times","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_transfer","payment_card","payment_coupon","balance","latest_date","r_times","contract_date","end_date","cancel_date","memo","reg_date","edit_date");
		$new_contract_id = Input_New_Data2("contract",$new_contract_field,$contract);
		
		//予約テーブルにキャンセル日以後のデータが新契約ID反映
		$GLOBALS['mysqldb']->query("update reservation set contract_id=".$new_contract_id." where del_flg=0 and customer_id=".$_POST['customer_id']." and hope_date>='".$_POST['cancel_date']."'");
	}

	// 当契約tableに反映----------------------------------------------------------------

	//ローン取消
	$_POST['loan_status'] = 4; 
	$_POST['loan_date'] = $_POST['cancel_date'];
	$contract_field2 = array("status","sales_id","balance","loan_status","loan_date","cancel_date","edit_date","memo");
	if($new_contract_id){
		$_POST['new_contract_id'] = $new_contract_id;
		array_push($contract_field2,  "new_contract_id");
	}
	// 更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);


	// Msg-----------------------------------------------------------------------------

	if( $_POST['contract_id'] && $_POST['sales_id'] ) {
		$gMsg = '（完了）';
		$complete_flg = 1;
		header("location: ../service/cancel.php?cancel_date=".$_POST['cancel_date']);
		exit;
	}else           $gMsg = '（登録しませんでした。)';
  }
}

// 詳細を取得----------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'  order by FIELD(status,5,0,4 )");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}

// 店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist_shop();

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// tax
if($data['contract_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}else{
	$tax = Get_Table_Row("basic"," WHERE id = 1");
	$tax2 = 1+$tax['value'];
}


// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by group_id,name" );
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_price[] = round(($result['price'] * (1+$tax['value'])),0);//税込
	$course_name[] = $result['name'];

}

// JSに渡すため、配列を文字列化----------------------------------------------------------------------------

$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);

//全角から半角へ
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

$pdf_param = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".$customer['name']."&course_name=".$course_list[$sales['course_id']]."&tax=".$tax['value']."&tax2=".$tax2;
$pdf_param.= "&fixed_price=".$sales['fixed_price']."&discount=".$sales['discount']."&price=".$sales['price']."&payment=".$data['payment'];
$pdf_param.= "&option_name=".$gOption[$sales['option_name']]."&option_price=".$sales['option_price']."&balance=".$sales['balance'];


?>
