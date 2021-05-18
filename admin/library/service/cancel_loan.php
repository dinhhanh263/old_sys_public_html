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

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	if(!$_POST['contract_id']){
		$gMsg = "<font color='red' size='-1'>※契約自体が未登録です。</font>";
	}elseif(!$_POST['cancel_date'] || $_POST['cancel_date']=="0000-00-00"){
		$gMsg = "<font color='red' size='-1'>※処理日を入力してください。</font>";
	}elseif($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<font color='red' size='-1'>※一ヶ月前のデータが編集不可です。</font>";
	}elseif($authority_level>0 && (!$_POST['loan_cancel_reason'] || $_POST['loan_cancel_reason']=="-")){
		$gMsg = "<font color='red' size='-1'>※「ローン取消理由」を選択してください。</font>";
	}elseif($authority_level>6 && !$_POST['rstaff_id']){
		$gMsg = "<font color='red' size='-1'>※「レジ担当」を選択してください。</font>";
	}elseif($authority_level>0 && $_POST['cancel_date']>date('Y-m-d')){
		$gMsg = "<font color='red' size='-1'>※未来日にローン取消できません。</font>";
	}else{

	// データ取得-----------------------------------------------------------------------

	if($_POST['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'] )."' ");
	// else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'] )."' and (status=0 or status=2  or status=5)  or status=7");//いらない？

	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=9 and contract_id = '".addslashes($contract['id'])."'");
	$course = Get_Table_Row("course", " WHERE del_flg=0 and id = '" . addslashes($contract['course_id']) . "'");

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
		$contract['reg_date'] = $contract['edit_date'] = date("Y-m-d H:i:s");


		// 契約待ち
		$contract['status'] = 7;

		// ローン取り消し時前契約ID
		$contract['loan_cancel_before_contract_id'] = $contract['id'];

		if($course['extension_period'] !="") {
			$new_contract_field = array("status", "reservation_id", "sales_id", "customer_id", "shop_id", "staff_id", "course_id", "times", "pay_complete_date", "fixed_price", "discount", "price", "payment", "payment_cash", "payment_transfer", "payment_card", "payment_coupon", "balance", "latest_date", "r_times", "contract_date", "end_date", "cancel_date", "memo", "reg_date", "edit_date", "conversion_flg", "extension_end_date", "loan_cancel_before_contract_id", "introducer_staff_id");
		} else {
			$new_contract_field = array("status", "reservation_id", "sales_id", "customer_id", "shop_id", "staff_id", "course_id", "times", "pay_complete_date", "fixed_price", "discount", "price", "payment", "payment_cash", "payment_transfer", "payment_card", "payment_coupon", "balance", "latest_date", "r_times", "contract_date", "end_date", "cancel_date", "memo", "reg_date", "edit_date", "conversion_flg", "loan_cancel_before_contract_id", "introducer_staff_id");
		}
		
		$new_contract_id = Input_New_Data2("contract",$new_contract_field,$contract);

		//予約テーブルにキャンセル日以後のデータが新契約ID反映
		$GLOBALS['mysqldb']->query("update reservation set contract_id=".$new_contract_id.",edit_date='".$_POST['edit_date']."' where del_flg=0 and customer_id=".$_POST['customer_id']." and contract_id=".$_POST['contract_id']." and hope_date>='".$_POST['cancel_date']."'") or die('query error'.$GLOBALS['mysqldb']->error);
	}

	// 当契約tableに反映----------------------------------------------------------------

	//ローン取消
	$_POST['loan_status'] = 4;
	$_POST['loan_date'] = $_POST['cancel_date'];
	$contract_field2 = array("status","sales_id","balance","loan_status","loan_date","cancel_date","edit_date","memo","loan_cancel_reason");
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

if( $_POST['contract_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

// }elseif( $_POST['customer_id'] != "" )  {
// 	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'  order by FIELD(status,5,0,4 )");
// 	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
// 	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
// 	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
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
}

//全角から半角へ
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

?>
