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

// 中途解約　contract.status=3　sales.type=5

$c_table = "contract";
$p_table = "contract_P";

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));
  
  if($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }else{	
	
	// データ取得-------------------------------------------------------------------

	if($_POST['id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['id'] )."' ");
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
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] - $_POST['charge']; //支払金額＝現金+カード+銀行振込+ローン-手数料
	
	//　解約手数料
	$_POST['option_name'] = 3; 

	if($_POST['payment_card'] == max(abs($_POST['payment_card']),abs($_POST['payment_cash']),abs($_POST['payment_transfer']))) {
		$_POST['option_card'] = $_POST['charge']; //カード
		$_POST['payment_card'] = $_POST['payment_card']- $_POST['charge'];

	}elseif($_POST['payment_cash'] == max(abs($_POST['payment_card']),abs($_POST['payment_cash']),abs($_POST['payment_transfer']))) {
		$_POST['option_price'] = $_POST['charge']; 
		$_POST['payment_cash'] = $_POST['payment_cash']- $_POST['charge'];
	}else{
		$_POST['option_transfer'] = $_POST['charge']; 
		$_POST['payment_transfer'] = $_POST['payment_transfer']- $_POST['charge'];
	}

	//　将来の施術予約をキャンセル処理,再契約の人がどうする？初回のみ処理?---------------------------------

	if($contract['status']==0 || $contract['status']==7) 
		$GLOBALS['mysqldb']->query("update reservation set type=3 where type=2 and del_flg=0 and contract_id=".$contract['id']." and customer_id=".$_POST['customer_id']." and hope_date>'".$_POST['cancel_date']."'");
	
	//　売上tableに反映------------------------------------------------------------------------

	// 中途解約
	$_POST['type'] = 5; 
	$_POST['contract_id'] = $contract['id'];
	$_POST['times'] = $contract['times'];
	$_POST['multiple_course_id']=$contract['course_id'];
	$sales_field  = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","times","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","option_name","option_price","option_card","option_transfer","balance","memo","pay_date","cancel_date","reg_date","edit_date");
	$sales_field2 = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","times","fixed_price","discount","payment","payment_cash","payment_card","payment_transfer","payment_loan","charge","balance","option_name","option_price","option_card","option_transfer","memo","pay_date","cancel_date","edit_date");

	//　更新 or 新規-------------------------------------------------------------------------

	// 再度精算、前回精算の取り消し
	if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	// 契約tableに反映---------------------------------------------------------------------------

	$contract_field2 = array("status","sales_id","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","cancel_date","edit_date","memo","edit_date");
	
	// ローン取消後
	if($contract['status']==7){
		$_POST['wait_flg']=1;
		array_push($contract_field2 , "wait_flg"); 
	} 

	// 更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);


	// 親契約tableに反映---------------------------------------------------------------------------

	$contract_p_field2 = array("multiple_course_id","status","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","edit_date");

	// 親契約tableに登録するためのデータを新しく作成する
	$data_array = Get_Table_Array($c_table,"*"," WHERE status=0 and del_flg=0 and pid = '".addslashes($contract['pid'])."'");
	$data_p     = Get_Table_Row($p_table," WHERE del_flg=0 and id = '".addslashes($contract['pid'])."'");
	$fixed_price_sum = 0; // コース金額の合計(複数)
	foreach ($data_array as $key => $value) {
		$multiple_course_array[] = $value['course_id'];    // コースID
		$new_fixed_price        += $value['fixed_price'];        // コース金額合計
		$new_discount           += $value['discount'];           // 割引金額合計
		$new_price              += $value['price'];              // (割引後)請求金額合計
		$new_payment            += $value['payment'];            // 入金金額合計
		$new_payment_cash       += $value['payment_cash'];       // 入金金額(現金)
		$new_payment_card       += $value['payment_card'];       // 入金金額(カード)
		$new_payment_transfer   += $value['payment_transfer'];   // 入金金額(振込)
		$new_payment_loan       += $value['payment_loan'];       // 入金金額(ローン)
		$new_balance            += $value['balance'];            // 売掛金
	}
	$new_multiple_course_id           = implode(',',$multiple_course_array);  // 複数コースID

	// 登録項目をセット
	$_POST['multiple_course_id']  = $new_multiple_course_id;// 複数コースID
	$_POST['fixed_price']         = $fixed_price_sum;       // コース金額合計
	$_POST['discount']            = $new_discount;			// 割引金額合計
	$_POST['price']               = $new_price;		    	// (割引後)請求金額合計
	$_POST['payment']             = $new_payment;			// 入金金額合計
	$_POST['payment_cash']        = $new_payment_cash;		// 入金金額(現金)
	$_POST['payment_card']        = $new_payment_card;		// 入金金額(カード)
	$_POST['payment_transfer']    = $new_payment_transfer;	// 入金金額(振込)
	$_POST['payment_loan']        = $new_payment_loan;		// 入金金額(ローン)
	$_POST['balance']             = $new_balance;		    // 売掛金

	// 現金、カード、振込、ローン
	$status_data_array = Get_Table_Array($c_table,"*"," WHERE del_flg=0 and pid = '".addslashes($contract['pid'])."'");
	$_POST['status']   = Update_Contract_P_Status($status_data_array,$contract['customer_id']);

	// 上記新しい契約データを親契約テーブルに更新する
	// 契約ステータスは、優先度の高いステータスに更新する
	$_POST['pid'] = Update_Data($p_table,$contract_p_field2,$contract['pid']); 

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

	// 税込
	$course_price[] = round(($result['price'] * (1+$tax['value'])),0);
	$course_name[] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}





// JSに渡すため、配列を文字列化--------------------------------------------------------------

$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);


// 詳細を取得----------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($c_table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$data_p = Get_Table_Row($p_table," WHERE del_flg=0 and id = '".addslashes($data['pid'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($c_table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc limit 1");
	$data_p = Get_Table_Row($p_table," WHERE del_flg=0 and id = '".addslashes($data['pid'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}
if($data['sales_id'] != 0 ) {
	if($sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'")){
		$payed_price_p = $sales['fixed_price'] - $sales['discount'] - $sales['price']; // 親契約より売掛残金を算出
	}else{
		// 支払済金額
		//$payed_price = $data['fixed_price'] - $data['discount'] - $data['balance'];
		$payed_price_p = $data_p['fixed_price'] - $data_p['discount'] - $data_p['balance']; // 親契約より売掛残金を算出
	}
}else{
	// 支払済金額
	//$payed_price =$data['fixed_price'] - $data['discount'] - $data['balance'];
	$payed_price_p = $data_p['fixed_price'] - $data_p['discount'] - $data_p['balance']; // 親契約より売掛残金を算出
}

// 消化単価
// $per_price = $data['times'] ? round(($data['fixed_price']-$data['discount'])/$data['times']) : 0;
$per_price = $data['unit_price'];

// 消化金額
$usered_price = $per_price * $data['r_times'];		

// 月額が0						
if($course_type[$data['course_id']]) $remained_price =0; 
else{
	if($sales['id'])$remained_price = -$sales['payment'];

	// 残金
	//else $remained_price = $payed_price_p - $usered_price;
	// 親契約の売掛残金-解約するコースの請求金額
	else $remained_price = ($sales['id'] ? ($sales['balance']-$sales['price']-$payed_price_p) : $data_p['balance']-$data['price']-$payed_price_p) - $usered_price;
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
$return_price = $remained_price - $charge; 

// 返金額,table登録用
$return_price0 = 0-$return_price;

$cancel_name = $course_type[$data['course_id']] ? "月額退会" : "中途解約";

?>
