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

// $table = "contract";
$table = "contract_P";
//var_dump($_POST);exit;
//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  
  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));
  
  if($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }elseif($_POST['confirm_payment'] && !($_POST['payment_cash']+$_POST['payment_card']+$_POST['payment_transfer']+$_POST['payment_loan'])){
  	$gMsg = "<font color='red' size='-1'>※入金額が入力していません。</font>";
  }else{
	
	//データ取得-------------------
	//status=0:クーリングオフ新規；　status=2：クーリングオフ編集；　status=5：ローン取消後クーリングオフ新規
	$contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and id = '".addslashes($_POST['pid'] )."' and (status=0 or status=2 or status=5 or status=7)  order by id DESC");
	$pid_contract = Get_Table_Array("contract","*"," WHERE del_flg=0 and pid = '".addslashes($contract_p['id'])."' and (status=0 or status=2 or status=5 or status=7)  order by id DESC");
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=4 and pid = '".addslashes($contract_p['id'])."'");

	//POST INPUT--------------
	$_POST['status'] = 2; // cooling off
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
	$_POST['balance'] = 0; //売掛金
	//$_POST['pay_date'] = $_POST['cancel_date'] = $contract['cancel_date'] ? $contract['cancel_date'] : date('Y-m-d');
	$_POST['pay_date'] = $_POST['cancel_date'];
	//if(!$_POST['payment'])$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ; //支払金額＝現金+カード+銀行振込+ローン
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ; //支払金額＝現金+カード+銀行振込+ローン

	//将来の施術予約をキャンセル処理,再契約の人がどうする？初回のみ処理?----------------------------------------------------------------
	if($contract_p['status']==0 || $contract_p['status']==7) $GLOBALS['mysqldb']->query("update reservation set type=3 where type=2 and del_flg=0 and pid=".$contract_p['id']." and customer_id=".$_POST['customer_id']." and hope_date>'".$_POST['cancel_date']."'");

	//売上tableに反映-------------
	$_POST['type'] = 4; // cooling off
	$_POST['contract_id'] = $contract['id'];
	$_POST['pid'] = $contract_p['id']; // 追加
	$_POST['multiple_course_id'] = $contract_p['multiple_course_id']; // 複数コースID
	$sales_field  = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","cancel_date","reg_date","edit_date");
	$sales_field2 = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","cancel_date","edit_date");
	//更新 or 新規
	if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);//再度精算、前回精算の取り消し
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	//契約tableに反映--------------
	$contract_p_field2 = array("status","sales_id","balance","cancel_date","edit_date","memo");
	//ローン取消後のクーリングオフ
	if($contract_p['status']==7){
			$_POST['wait_flg']=1;
			array_push($contract_p_field2 , "wait_flg"); 
	} 
	//更新
	if($contract_p['id']) $_POST['pid'] = Update_Data("contract_P",$contract_p_field2,$contract_p['id']);

	//Msg-----------------------
	$contract_field2 = array("status","sales_id","cancel_date","edit_date","memo");
	foreach ($pid_contract as $key => $value) {
		// 契約テーブル分すべて更新する
		if($value['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$value['id']);
	}


	//Msg-----------------------
	if( $_POST['pid'] && $_POST['contract_id'] && $_POST['sales_id'] ) {
		$gMsg = '（完了）';
		$complete_flg = 1;
		header("location: ../service/cancel.php?cancel_date=".$_POST['cancel_date']);
		exit;
	}else           $gMsg = '（登録しませんでした。)';
  }
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['pid'] != "" )  {
	// $data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$contract_p = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['pid'])."'");
	$pid_contract = Get_Table_Array("contract","*"," WHERE del_flg=0 and pid = '".addslashes($_POST['pid'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($contract_p['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($contract_p['shop_id'])."'");
	//$staff = Get_Table_Row("staff"," WHERE del_flg=0 and id = '".addslashes($data['staff_id'])."'");
	if($contract_p['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($contract_p['sales_id'])."'");

}elseif( $_POST['customer_id'] != "" )  {
	//$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$contract_p = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($contract_p['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($contract_p['shop_id'])."'");
	//$staff = Get_Table_Row("staff"," WHERE id = '".addslashes($data['staff_id'])."'");
	if($contract_p['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($contract_p['sales_id'])."'");
}

//ローンは既収金額ではない
if($contract_p['loan_status'] ==1){
	$contract_p['payment'] = $contract_p['payment'] - $contract_p['payment_loan'] ;
}else{
	$contract_p['payment'] = $contract_p['payment'];
}

// 契約数からコースの数を取得し、配列に入れる（クーリングオフレジ清算表示用）
// 親契約IDに紐づくコース情報
if($pid_contract){
	$pid_contract_count = count($pid_contract)-1; //契約数
	$times_sum = 0; // コースの回数の合計（初期化）
	for($i=0;$i<=$pid_contract_count;$i++){
		$course_id[$i]     = $pid_contract[$i]['course_id'];    // コースID
		$fixed_price[$i]   = $pid_contract[$i]['fixed_price'];	// 商品金額
		$per_price[$i]     = $pid_contract[$i]['unit_price'];	// 消化単価
		$times[$i]         = $pid_contract[$i]['times'];		// 回数
		$r_times[$i]       = $pid_contract[$i]['r_times'];		// 消化回数
		// 契約部位の表示（領収書用）
		if($pid_contract[$i]['contract_part']){
			$single_part  = array();  // 部位の配列
			$single_parts = "";      // 部位（カンマ区切り） 
			$single_part  = explode(",", $pid_contract[$i]['contract_part']);
			// 部位名を取得する
			foreach ($single_part as $key => $value) {
				$single_parts[] = $gContractParts[$value];
			}
			$contract_part_receipt[$i]  = implode(",", $single_parts); // 部位(カンマ区切り)
		}
	}
}


//店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax
if($data['contract_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}else{
	$tax = Get_Table_Row("basic"," WHERE id = 1");
	$tax2 = 1+$tax['value'];
}


//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by group_id,name" );
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_price[] = round(($result['price'] * (1+$tax['value'])),0);//税込
	$course_name[] = $result['name'];

}

//JSに渡すため、配列を文字列化----------------------------------------------------------------------------
$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);
//var_dump($sales);
$shop_address = str_replace("　", " ", $shop['address']);//全角から半角へ
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

$mpdf_cooling_off = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".$customer['name']."&tax=".$tax['value']."&tax2=".$tax2;
$mpdf_cooling_off.= "&fixed_price=".$sales['fixed_price']."&discount=".$sales['discount']."&price=".$sales['price']."&payment=".$data['payment'];
$mpdf_cooling_off.= "&option_name=".$gOption[$sales['option_name']]."&option_price=".$sales['option_price']."&balance=".$sales['balance'];
// 複数コース時
if($course_id[0])$mpdf_cooling_off.= "&course_name=".$course_list[$course_id[0]];
if($course_id[1])$mpdf_cooling_off.= "&course_name2=".$course_list[$course_id[1]];
if($course_id[2])$mpdf_cooling_off.= "&course_name3=".$course_list[$course_id[2]];
if($course_id[3])$mpdf_cooling_off.= "&course_name4=".$course_list[$course_id[3]];
// 選べる部位がある場合
if($contract_part_receipt[0])$mpdf_cooling_off.= "&contract_part=".$contract_part_receipt[0];
if($contract_part_receipt[1])$mpdf_cooling_off.= "&contract_part2=".$contract_part_receipt[1];
if($contract_part_receipt[2])$mpdf_cooling_off.= "&contract_part3=".$contract_part_receipt[2];
if($contract_part_receipt[3])$mpdf_cooling_off.= "&contract_part4=".$contract_part_receipt[3];


?>
