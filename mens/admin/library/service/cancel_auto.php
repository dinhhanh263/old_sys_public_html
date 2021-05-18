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

//自動解約　contract.status=6　sales.type=12

$table = "contract";

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  
  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));
  
  if($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }else{

	// データ取得-------------------------------------------------------------------

	$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'] )."' and (status=0 or status=3 or status=5 or status=6 or status=7) order by id desc limit 1");
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=12 and contract_id = '".addslashes($contract['id'])."'");

	// POST INPUT---------------------------------------------------------------
	// 自動解約
	$_POST['status'] = 6; 
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
	// 売掛金
	$_POST['balance'] = 0; 
	$_POST['pay_date'] = $_POST['cancel_date'];
	// 支払金額＝現金+カード+銀行振込

	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer']  ; 

	// 将来の施術予約をキャンセル処理,再契約の人がどうする？初回のみ処理?----------------------------------

	if($contract['status']==0 || $contract['status']==7) $GLOBALS['mysqldb']->query("update reservation set type=3 where type=2 and del_flg=0 and contract_id=".$contract['id']." and customer_id=".$_POST['customer_id']." and hope_date>'".$_POST['cancel_date']."'");
	
	// 売上tableに反映------------------------------------------------------------------------
	// 自動解約
	$_POST['type'] = 12; 
	$_POST['contract_id'] = $contract['id'];
	$sales_field  = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","option_name","option_price","option_card","option_transfer","balance","memo","pay_date","cancel_date","reg_date","edit_date");
	$sales_field2 = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","balance","option_name","option_price","option_card","option_transfer","memo","pay_date","cancel_date","edit_date");

	// 更新 or 新規---------------------------------------------------------------------------

	// 再度精算、前回精算の取り消し
	if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
	// 売上計上（新規）
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);

	// 契約tableに反映--------------------------------------------------------------------------

	$contract_field2 = array("status","sales_id","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","cancel_date","edit_date","memo","edit_date");
	
	// ローン取消後
	if($contract['status']==7){
		$_POST['wait_flg']=1;
		array_push($contract_field2 , "wait_flg"); 
	} 

	// 更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);

	// Msg-------------------------------------------------------------------------------------

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
}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc limit 1");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}
if($data['sales_id'] != 0 ) {
	if($sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'")){
		$payed_price = $sales['fixed_price'] - $sales['discount'] - $sales['price'];

		// 支払済金額
		$payed_price = $data['price'] - $data['balance'] - $data['payment_loan'];
	}
}else{
	// 支払済金額
	$payed_price = $data['price'] - $data['balance'] - $data['payment_loan'];
}

// 消化単価
$per_price = $data['times'] ? round($data['price']/$data['times']) : 0;

// 消化金額
$usered_price = $per_price * $data['r_times'];

// 残金
$remained_price = $payed_price - $usered_price;


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
	$course_type[$result['id']] = $result['type'];

}

// JSに渡すため、配列を文字列化----------------------------------------------------------------------------
$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);



// 手数料：返金の10％、最大2万円,月額が手数料なし
if($course_type[$data['course_id']]) $charge = 0;
else{
	$charge = round(($data['fixed_price'] - $usered_price)*0.1);
	if($charge > 20000) $charge = 20000;
}

// 返金額
$return_price = $remained_price - $charge;

// 返金額,table登録用  
$return_price0 = 0-$return_price;

?>
