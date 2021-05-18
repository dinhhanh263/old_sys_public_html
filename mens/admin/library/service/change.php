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

//プラン変更.status=4　sales.type=6
//プラン変更時、変更日後直近（変更日含む）施術予約データの契約IDも合わせて変更

$table = "contract";

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

  if(!$_POST['course_id'] || $_POST['course_id']=="-"){
  	$gMsg = "<font color='red' size='-1'>※新コースが未選択です。</font>";
  }elseif($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }else{
	//データ取得-------------------------------------------------------------------
	if($_POST['id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_GET['id'])."' ");
	else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'] )."' and id= '".addslashes($_POST['id'] )."'　and (status=0 or status=4 or status=5 or status=7) and old_contract_id='' order by id desc limit 1");
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=6 and contract_id = '".addslashes($contract['id'])."'");
	if( $_POST['course_id'] != "" )  	 $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");
	if( $contract['course_id'] != "" )   $old_course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($contract['course_id'])."'");

	//POST INPUT---------------------------------------------------------------
	$_POST['status'] = 4; // プラン変更
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

	//パックプラン間の変更、旧契約日より継続。 月額からパック、またパックから月額への変更が処理日より
	//$_POST['contract_date'] = ($course['type'] || $old_course['type']) ? $_POST['cancel_date'] : $contract['contract_date'] ;
	//新契約日が処理日に統一
	$_POST['contract_date'] = $_POST['cancel_date'];
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ; //支払金額＝現金+カード+銀行振込+ローン
	$_POST['balance'] = $_POST['price'] - $_POST['payment'] ; //売掛金
	//if(!$_POST['balance']) $_POST['pay_complete_date'] = $_POST['cancel_date'];
	if($_POST['balance']<=0  && !$_POST['payment_loan']) $_POST['pay_complete_date'] = $_POST['cancel_date'];
	else $_POST['pay_complete_date'] = "0000-00-00";


	//新契約tableに反映------------------------------------------------------------------------
	//編集場合の対応？新契約がまた新規？
	$_POST['old_contract_id'] = $contract['id'];
	$_POST['old_course_id'] = $contract['course_id'];

	if(!$_POST['staff_id'])$_POST['staff_id'] = $contract['staff_id'];
	// 契約情報
	$_POST['pid']        = $contract['pid'];         // 親契約ID
	$_POST['times']      = $course['times'];         // コース回数
	// 新しい消化単価の計算
	$new_price = $_POST['fixed_price'] - $_POST['discount'];
	$unit_price_array    = Unit_Price_Calculation(
		array($_POST['course_id']=>$new_price),
		array($_POST['course_id']=>$_POST['times']),
		array($_POST['course_id']=>$_POST['discount'])
	);
	$_POST['unit_price'] = $unit_price_array['unit_price'][$_POST['course_id']];  // 消化単価
	$_POST['surplus_unit_price'] = $unit_price_array['surplus'][$_POST['course_id']];     // 消化単価余り

	// パックの時の所要時間(分)を入れる
	if($course['one_flg']==0){
		$_POST['part_time_sum'] = $gLengthNum[$course['length']];	// コースにかかる所要時間(分)
	}

	if($course['period']) {
		//新契約日が処理日に統一
		$_POST['end_date'] = date("Y-m-d",strtotime("+{$course['period']} day",strtotime($_POST['cancel_date'])));
	}

	// 変更後のえらべる部位
	if($_POST['contract_part'])$_POST['contract_part'] = implode(",", $_POST['contract_part']);

	$new_contract_field  = array("old_contract_id","old_course_id","pid","customer_id","shop_id","staff_id","course_id","reservation_id","contract_part","times","part_time_sum","pay_complete_date","fixed_price","discount","price","unit_price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","contract_date","end_date","reg_date","edit_date");
	$new_contract_field2 = array("old_contract_id","old_course_id","pid","customer_id",		     "staff_id","course_id","reservation_id","contract_part","times","part_time_sum","pay_complete_date","fixed_price","discount","price","unit_price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","contract_date","end_date","edit_date");
	//ローン取消後
	if($contract['status']==7){
		$_POST['wait_flg']=1;
		array_push($contract_field , "wait_flg");
		array_push($contract_field2 , "wait_flg");
	}
	//更新 or 新規
	if($contract['new_contract_id']) $_POST['new_contract_id'] = Update_Data("contract",$new_contract_field2,$contract['new_contract_id']);
	else $_POST['new_contract_id'] = Input_New_Data("contract",$new_contract_field);//新規

	//売上tableに反映------------------------------------------------------------------------
	$_POST['type'] = 6; // プラン変更
	$_POST['contract_id'] = $contract['id'];
	$_POST['fixed_price'] = $contract['fixed_price'];
	//$_POST['discount'] = $contract['discount'];

	$_POST['pay_date'] = $_POST['cancel_date'];
	$_POST['multiple_course_id'] = $_POST['course_id']; // 今回変更したコース(1つ)

	$sales_field  = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","multiple_course_id","reservation_id","fixed_price","discount","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","reg_date","edit_date");
	$sales_field2 = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","multiple_course_id","reservation_id","fixed_price","discount","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","edit_date");

	// 更新前のsalesレコード
	if($contract['sales_id']) $sales_data = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($contract['sales_id'])."'");

	//更新 or 新規
	if($contract['sales_id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$contract['sales_id']);//再度精算、前回精算の取り消し
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	//旧契約tableに反映---------------------------------------------------------------------------
	$_POST['new_course_id'] = $_POST['course_id'];
	if(!$_POST['if_cancel_date']) $_POST['if_cancel_date'] =0;
	$contract_field2 = array("new_contract_id","new_course_id","status","pid","sales_id","cancel_date","if_cancel_date","edit_date","memo");
	//更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);

	//予約tableに新契約IDを反映---------------------------------------------------------------------------
	/*$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and type=2 and hope_date >= '".$_POST['cancel_date']."' order by hope_date limit 1");
	$reservation_field2 = array("contract_id","edit_date");
	//更新
	Update_Data("reservation",$reservation_field2,$reservation['id']);*/

	//プラン変更適応日
	$contract_p_field2 = array("multiple_course_id","status","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","edit_date","pay_complete_date");

	// 親契約tableに登録するためのデータを新しく作成する
	$data_array = Get_Table_Array($table,"*"," WHERE status=0 and del_flg=0 and pid = '".addslashes($contract['pid'])."'");
	$data_p     = Get_Table_Row("contract_P"," WHERE del_flg=0 and id = '".addslashes($contract['pid'])."'");
	$data_old   = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['old_contract_id'])."'"); // 旧契約情報
	$fixed_price_sum = 0; // コース金額の合計(複数)
	$new_price       = 0; // 請求金額合計(初期化)
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

	// 更新時は一度レジ清算した前のデータを引いてから計算する
	if($sales_data['id']){
		$_POST['discount']         -= $sales_data['discount'];
		$_POST['balance']          -= $sales_data['balance'];
		$_POST['price']            -= $sales_data['price'];
		$_POST['payment']          -= $sales_data['payment'];
		$_POST['payment_cash']     -= $sales_data['payment_cash'];
		$_POST['payment_transfer'] -= $sales_data['payment_transfer'];
		$_POST['payment_card']     -= $sales_data['payment_card'];
		$_POST['payment_loan']     -= $sales_data['payment_loan'];
		$_POST['balance']          -= $sales_data['balance'];
	}

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
	// $_POST['balance']             = $data_p['balance'] + $_POST['balance'];                           // 売掛金残っていたら合算する

	// 現金、カード、振込、ローン
	$status_data_array = Get_Table_Array($table,"*"," WHERE del_flg=0 and pid = '".addslashes($contract['pid'])."'");
	$_POST['status']   =Update_Contract_P_Status($status_data_array,$_POST['customer_id']);

	//更新
	$_POST['pid'] = Update_Data("contract_P",$contract_p_field2,$contract['pid']); 

	//プラン変更適応日
	//処理日含み
	if($_POST['if_cancel_date']){
		//同時複数予約を考慮
		$GLOBALS['mysqldb']->query("update reservation set contract_id=".$_POST['new_contract_id']." ,course_id=".$_POST['course_id'].",edit_date='".$_POST['edit_date']."'  where  customer_id = '".$_POST['customer_id']."' and  hope_date = '".$_POST['cancel_date']."' order by reg_date desc limit 1");
	// 処理日以後
	}else{
		$GLOBALS['mysqldb']->query("update reservation set contract_id=".$_POST['old_contract_id']." ,course_id=".$_POST['old_course_id'].",edit_date='".$_POST['edit_date']."'  where  customer_id = '".$_POST['customer_id']."' and  hope_date = '".$_POST['cancel_date']."' limit 3");
	}

	$GLOBALS['mysqldb']->query("update reservation set contract_id=".$_POST['new_contract_id']." ,course_id=".$_POST['course_id'].",edit_date='".$_POST['edit_date']."'  where  customer_id = '".$_POST['customer_id']."' and  hope_date > '".$_POST['cancel_date']."' limit 3"); //3件まで、キャンセルデータも予想して？

	//Msg------------------------------------------------------------------------------
	if( $_POST['contract_id'] && $_POST['sales_id'] ) {
		$gMsg = '（完了）';
		$complete_flg = 1;
		header("location: ../service/cancel.php?cancel_date=".$_POST['cancel_date']);
		exit;
	}else           $gMsg = '（登録しませんでした。)';
  }
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['id'] )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

//プラン変更：status=0
}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and (status=0 or status=5 or status=7 or (status=4  and old_contract_id='' and new_contract_id<>'')) and customer_id = '".($_POST['customer_id'])."' order by FIELD(status,4,5,7,0 )");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}

// 契約情報
if($data['new_contract_id']){
	$new_contract = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($data['new_contract_id'])."' order by id desc"); // 新契約情報
	$old_contract = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($new_contract['old_contract_id'])."' order by id desc"); // 旧契約情報
	//  新部位名
	$new_contract_part =  explode(",",$new_contract['contract_part']);
	if($new_contract_part){
		foreach ($new_contract_part as $key => $value) {
			$new_all_key[] = $value;
			$new_all_parts[] = $gContractParts[$value];
		}
		$new_contract_parts_key = implode(",", $new_all_key); // 部位(キーのカンマ区切り)
		$new_contract_parts = implode(",", $new_all_parts); // 部位(カンマ区切り)
	}
	//  旧部位名
	$old_contract_part =  explode(",",$old_contract['contract_part']);
	if($old_contract_part){
		foreach ($old_contract_part as $key => $value) {
			$old_all_key[] = $value;
			$old_all_parts[] = $gContractParts[$value];
		}
		$old_contract_parts_key = implode(",", $old_all_key); // 部位(キーのカンマ区切り)
		$old_contract_parts = implode(",", $old_all_parts); // 部位(カンマ区切り)
	}
}
if(!$new_contract['old_contract_id']){ // 旧契約IDがなければ
	//  現在の部位名を旧部位名とする
	$contract_part =  explode(",",$data['contract_part']);
	if($contract_part){
		foreach ($contract_part as $key => $value) {
			$all_key[] = $value;
			$all_parts[] = $gContractParts[$value];
		}
		$old_contract_parts_key = implode(",", $all_key); // 部位(キーのカンマ区切り)
		$old_contract_parts = implode(",", $all_parts); // 部位(カンマ区切り)
	}	
} 
//if($data['new_contract_id'])$new_contract = Get_Table_Row($table," WHERE del_flg=0 and course_id = '".addslashes($data['course_id'])."' order by id desc");


$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($data['course_id'])."'");

// $per_price = $data['times'] ? round($data['price']/$data['times']) : 0;	//消化単価
$per_price = $data['unit_price'];	//消化単価
if($course['type'] && $data['r_times']>2)$usered_price = $per_price * $course['times'];
else $usered_price = $per_price * $data['r_times'];								// 消化金額

if($data['status']==2 || $data['status']==3 || $data['status']==5) $payed_price =0; //解約の場合、支払済金額リセット
else $payed_price = $data['price'] - $data['balance']; // 支払済金額
// 個別残金が登録されていなかったら、初回レジ清算とみなし、
// 値引き後金額（旧） の計算を 消化単価×コース回数の金額で計算する
if($data['price']==0)$payed_price = $per_price*$data['times'];
$remained_price = $payed_price - $usered_price;  // 残金

$return_price = $remained_price - $charge;  // 返金額
$return_price0 = 0-$return_price;			// 返金額,table登録用

//店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax/
/*
if($data['contract_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}else{*/
	$tax_data = Get_Table_Row("basic"," WHERE id = 1");
	$tax =$tax_data['value'];
	$tax2 = 1+$tax_data['value'];
//}


//courseリスト
//$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE one_flg=0 and del_flg=0 AND status=2 AND old_flg=0 order by group_id,name" );
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE one_flg=0 and del_flg=0 AND status=2 AND old_flg=0 AND id<=1000 order by group_id,name" ); // 20160602 返金保証回数終了したPREMIUMコースは選べない
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_price[] = round(($result['price'] * $tax2),0);//税込
	$course_name[] = $result['name'];

}

//JSに渡すため、配列を文字列化----------------------------------------------------------------------------
$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);

//PDF用のパラメータ
$shop_address = str_replace("　", " ", $shop['address']);//全角から半角へ
list($shop_address1,$shop_address2) = explode(" ", $shop_address);
$mpdf_change  = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] );
$mpdf_change .= "&old_course_name=".$course_list[$data['course_id']]."&fixed_price=".$data['fixed_price']."&discount=".$data['discount']."&price=".$per_price*$data['times']."&payed_price=".$payed_price."&r_times=".$data['r_times']."&times=".$data['times']."&per_price=".$per_price."&usered_price=".$usered_price."&remained_price=".$remained_price;
$mpdf_change .= "&new_course_name=".$course_list[$data['new_course_id']]."&new_fixed_price=".$new_contract['fixed_price']."&new_price=".$new_contract['price']."&payment=".$new_contract['payment']."&balance=".$new_contract['balance'];
$mpdf_change .= "&new_contract_part=".$new_contract_parts."&old_contract_part=".$old_contract_parts; // えらべる部位

if(isset($new_contract['fixed_price']) && isset($new_contract['price']) && isset($new_contract['payment']) && isset($new_contract['balance'])){
	$receipt_flg = 1;
}

?>
