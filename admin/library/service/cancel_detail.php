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
// 中途解約　contract.status=3　sales.type=5
$table = "contract";
// 編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

  if($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }else{


	// データ取得-------------------------------------------------------------------
	if($_POST['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'] )."' ");
	else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'] )."' and (status=0 or status=3 or status=5 or status=7) order by id desc limit 1");

	// ローン取消後のプラン変更と支払方法変更のため、旧契約データをコピー。役務消化後の解約？
	if($contract['status']==5){
		//　契約待ち
		$contract['status'] =5 ;
		$old_contract_field  = array("status","reservation_id","sales_id","customer_id","shop_id","staff_id","course_id","times","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_transfer","payment_card","payment_coupon","balance","latest_date","r_times","contract_date","end_date","cancel_date","memo","reg_date","edit_date");
		$old_contract_id = Input_New_Data2("contract",$old_contract_field,$contract);
		$contract = Get_Table_Row("contract"," WHERE id=".$old_contract_id);
	}
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=5 and contract_id = '".addslashes($contract['id'])."'");
	//　POST INPUT---------------------------------------------------------------
	// 中途解約
	$_POST['status'] = 3;
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

	//　売掛金
	$_POST['balance'] = 0;
	$_POST['pay_date'] = $_POST['cancel_date'];
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] - $_POST['charge'] - $_POST['charge2'] - $_POST['charge3']; //支払金額＝現金+カード+銀行振込+ローン-手数料

	//　解約手数料
	$_POST['option_name'] = 3;
	if($_POST['payment_card'] && $_POST['payment_card'] == max(abs($_POST['payment_card']),abs($_POST['payment_cash']),abs($_POST['payment_transfer']))) {
		$_POST['option_card'] = $_POST['charge'] + $_POST['charge2'] + $_POST['charge3']; //カード
		$_POST['payment_card'] = $_POST['payment_card']- $_POST['charge'] - $_POST['charge2'] - $_POST['charge3'];
	}elseif($_POST['payment_cash'] && $_POST['payment_cash'] == max(abs($_POST['payment_card']),abs($_POST['payment_cash']),abs($_POST['payment_transfer']))) {
		$_POST['option_price'] = $_POST['charge'] + $_POST['charge2'] + $_POST['charge3'];
		$_POST['payment_cash'] = $_POST['payment_cash']- $_POST['charge'] - $_POST['charge2'] - $_POST['charge3'];
	}else{
		$_POST['option_transfer'] = $_POST['charge'] + $_POST['charge2'] + $_POST['charge3'];
		$_POST['payment_transfer'] = $_POST['payment_transfer']- $_POST['charge'] - $_POST['charge2'] - $_POST['charge3'];
	}
	//　将来の施術予約をキャンセル処理,再契約の人がどうする？初回のみ処理?---------------------------------
	if($contract['status']==0 || $contract['status']==7)
		$GLOBALS['mysqldb']->query("update reservation set type=3 where type=2 and del_flg=0 and contract_id=".$contract['id']." and customer_id=".$_POST['customer_id']." and hope_date>'".$_POST['cancel_date']."'") or die('query error'.$GLOBALS['mysqldb']->error);

	//　売上tableに反映------------------------------------------------------------------------
	// 中途解約
	$_POST['type'] = 5;
	// $_POST['contract_id'] = $contract['id'];
	$_POST['times'] = $contract['times'];
	$sales_field  = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","times","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","charge2","charge3","option_name","option_price","option_card","option_transfer","balance","memo","pay_date","cancel_date","reg_date","edit_date","terminate_day");
	$sales_field2 = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","times","fixed_price","discount","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","charge2","charge3","balance","option_name","option_price","option_card","option_transfer","memo","pay_date","cancel_date","edit_date","terminate_day");
	//　更新 or 新規-------------------------------------------------------------------------
	// 再度精算、前回精算の取り消し
	if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）
	// 契約tableに反映---------------------------------------------------------------------------
	$contract_field2 = array("status","sales_id","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","cancel_date","edit_date","memo","edit_date","terminate_day");

	// ローン取消後
	if($contract['status']==7){
		$_POST['wait_flg']=1;
		array_push($contract_field2 , "wait_flg");
	}
	// 更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);

	// Msg--------------------------------------------------------------------------------
	if( $_POST['contract_id'] && $_POST['sales_id'] ) {
		$gMsg = '（完了）';
		$complete_flg = 1;
		header("location: ../service/cancel.php?cancel_date=".$_POST['cancel_date']);
		exit;
	}else           $gMsg = '（登録しませんでした。)';
  }
}
// 店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist("shop");
// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);
$course_list[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['contract_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}
// elseif( $_POST['customer_id'] != "" )  {
// 	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc limit 1");
// 	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
// 	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
// }
if($data['sales_id'] != 0 ) {
	if($sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'")){
		$payed_price = $sales['fixed_price'] - $sales['discount'] - $sales['price'];
	}else{
		// 支払済金額
		$payed_price = $data['fixed_price'] - $data['discount'] - $data['balance'];
	}
}else{
	// 支払済金額
	$payed_price =$data['fixed_price'] - $data['discount'] - $data['balance'];
}
// 消化単価
$per_price = $data['times'] ? round(($data['fixed_price']-$data['discount'])/$data['times']) : 0;
// 消化金額
$usered_price = $per_price * $data['r_times'];
// 月額が0
if($course_type[$data['course_id']]) $remained_price =0;
else{
	if($sales['id'])$remained_price = -$sales['payment'];
	// 残金
	else $remained_price = $payed_price - $usered_price;
}
// 手数料：返金の10％、最大2万円,月額が手数料なし
if($course_type[$data['course_id']]) $charge = 0;
elseif($data['sales_id']) $charge = $sales['charge'];
else{
	// 値引き後基準
	$charge = round(($data['fixed_price'] - $data['discount'] - $usered_price)*0.1);
	if($charge > 20000) $charge = 20000;
}
// 返金額,月額が返金なし
$return_price = $remained_price - $charge - $sales['charge2']- $sales['charge3'];
// 返金額,table登録用
$return_price0 = 0-$return_price;
$cancel_name = $course_type[$data['course_id']] ? "月額退会" : "中途解約";
?>