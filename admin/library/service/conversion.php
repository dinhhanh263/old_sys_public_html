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

// コース情報取得
if( $_POST['course_id'] != "" ) $course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($_POST['course_id'])."'");

// 編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  if($_POST['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($_POST['contract_id'] )."' ");
  else $contract = Get_Table_Row("contract"," WHERE del_flg=0
  											  AND customer_id = '".addslashes($_POST['customer_id'] )."'
  											  AND (status=0 OR status=4 OR status=5 OR status=7)
  											  AND old_contract_id=''
  											  ORDER BY id DESC
  											  LIMIT 1");

  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

  if (!$_POST['course_id'] || $_POST['course_id']=="-") {
  	$gMsg = "<font color='red' size='-1'>※新コースが未選択です。</font>";
	}elseif(!$_POST['shop_id']){
		$gMsg = "<font color='red' size='-1'>※店舗が未選択です。</font>";
  }elseif($authority_level>0 && $_POST['cancel_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";

  // 同じコース、同じ契約日、初回のみ（編集以外）
  }elseif($authority_level>0 && !$contract['new_contract_id'] && $_POST['course_id']==$contract['course_id'] && $contract['contract_date']==$_POST['cancel_date']){
		$gMsg = "<span style='color:red;font-size:13px;'>※二重プラン変更です。</span>";

  }elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && !$_POST['loan_company_id']){
	$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※ローン会社を選択してください。</span>";

  }elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && $_POST['payment_loan']%10000<>0){
	$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※ローン金額が正しくありません。</span>";

  }elseif($course['new_flg'] && !$_POST['start_ym']){
	     $gMsg = "<font color='red' size='-1'>※「施術開始年月」を選択してください。</font>";

  }elseif($authority_level>0 && !$_POST['staff_id']){
  	$gMsg = "<font color='red' size='-1'>※「ミドルカウンセリング担当」を選択してください。</font>";

  }elseif($authority_level>6 && !$_POST['rstaff_id']){
    $gMsg = "<font color='red' size='-1'>※「レジ担当」を選択してください。</font>";

  }else{

	// データ取得-------------------------------------------------------------------
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 AND type=6 AND contract_id = '".addslashes($contract['id'])."'");
	// 旧コース情報取得
	if( $contract['course_id'] != "" )   $old_course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($contract['course_id'])."'");

	// POST INPUT---------------------------------------------------------------
	$_POST['status'] = 4; // プラン変更
	$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

	// 新契約日が処理日に統一
	$_POST['contract_date'] = $contract['contract_date'];

	// 支払金額＝現金+カード+銀行振込+ローン
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ;

	// 売掛金
	$_POST['balance'] = $_POST['price'] - $_POST['payment'] ;

	// if(!$_POST['balance']) $_POST['pay_complete_date'] = $_POST['cancel_date'];
	if($_POST['balance']<=0  && !$_POST['payment_loan']) $_POST['pay_complete_date'] = $_POST['cancel_date'];
	else $_POST['pay_complete_date'] = "0000-00-00";


	// 新契約tableに反映------------------------------------------------------------------------
	// 編集場合の対応？新契約がまた新規？
	$_POST['old_contract_id'] = $contract['id'];
	$_POST['old_course_id'] = $contract['course_id'];

	$_POST['times'] = $course['times'];
	$_POST['introducer_staff_id'] = $contract['introducer_staff_id'];

	if($course['period']) {
		// 新契約日が処理日に統一
		$_POST['end_date'] = date("Y-m-d",strtotime("+{$course['period']} day",strtotime($contract['contract_date'])));
	}

	// ローン申込日記入 add by ka 20180629
	if($_POST['payment_loan']) $_POST['loan_application_date']=$_POST['cancel_date'];
	else $_POST['loan_application_date'] = "0000-00-00";

	$new_contract_field  = array("old_contract_id",
								 "old_course_id",
								 "customer_id",
								 "shop_id",
								 "staff_id",
								 "course_id",
								 "times",
								 "pay_complete_date",
								 "fixed_price",
								 "discount",
								 "price",
								 "payment",
								 "payment_cash",
								 "payment_card",
								 "payment_transfer",
								 "payment_loan",
								 "loan_application_date",
								 "start_ym",
								 "balance",
								 "r_times",
								 "conversion_flg",
								 "memo",
								 "contract_date",
								 "latest_date",
								 "end_date",
								 "reg_date",
								 "edit_date",
								"introducer_staff_id");

	$new_contract_field2 = array("old_contract_id",
								 "old_course_id",
								 "customer_id",
								 "shop_id",
								 "staff_id",
								 "course_id",
								 "times",
								 "pay_complete_date",
								 "fixed_price",
								 "discount",
								 "price",
								 "payment",
								 "payment_cash",
								 "payment_card",
								 "payment_transfer",
								 "payment_loan",
								 "loan_application_date",
								 "start_ym",
								 "balance",
								 "r_times",
								 "conversion_flg",
								 "memo",
								 "contract_date",
								 "end_date",
								 "edit_date",
								 "extension_end_date",
								"introducer_staff_id");

	// ローン取消後
	if($contract['status']==7){
		$_POST['wait_flg']=1;
		array_push($contract_field , "wait_flg");
		array_push($contract_field2 , "wait_flg");
	}

	if( is_numeric($_POST['payment_loan']) || $_POST['payment_loan']>0){
		array_push($new_contract_field , "loan_company_id");
		array_push($new_contract_field2 , "loan_company_id");
	}
	// 入力されたローンの取消処理
	if( $sales['payment_loan'] && !$_POST['payment_loan']){
		$_POST['loan_company_id'] = 0;
		array_push($contract_field2 , "loan_company_id");
	}

	// 11/01以降の新コースで猶予期間設定
	if (!empty($course['extension_period'])) {
	    $_POST['extension_end_date'] = date("Y-m-d",strtotime("+{$course['period']} day + {$course['extension_period']} day",strtotime($contract['contract_date'])));
	    array_push($new_contract_field , "extension_end_date");
	    // array_push($new_contract_field2 , "extension_end_date");
	} else {
		$_POST['extension_end_date'] = "NULL"; // レジ精算後に新パック→月額に修正した場合、extension_end_dateをNULLに戻す
	}

	// 編集時、パックで「施術開始予定年月」が入っていた場合、初期値に戻す 2016/10/18 add by shimada
	if($course['type']==0 && $_POST['start_ym']<>0){
	    $_POST['start_ym'] = 0;
	}

	// 更新 OR 新規
	if($contract['new_contract_id']) $_POST['new_contract_id'] = Update_Data_Null("contract",$new_contract_field2,$contract['new_contract_id']);
	else $_POST['new_contract_id'] = Input_New_Data("contract",$new_contract_field);//新規

	// 売上tableに反映------------------------------------------------------------------------
	$_POST['type'] = 6; // プラン変更
	$_POST['contract_id'] = $contract['id'];

	$_POST['pay_date'] = $_POST['cancel_date'];


	$sales_field  = array("contract_id",
						  "type",
						  "customer_id",
						  "shop_id",
						  "staff_id",
						  "rstaff_id",
						  "course_id",
						  "fixed_price",
						  "discount",
						  "price",
						  "pay_type",
						  "payment",
						  "payment_cash",
						  "payment_card",
						  "payment_transfer",
						  "payment_loan",
						  "balance",
						  "memo",
						  "pay_date",
						  "reg_date",
						  "edit_date");

	$sales_field2 = array("contract_id",
						  "type",
						  "customer_id",
						  "shop_id",
						  "staff_id",
						  "rstaff_id",
						  "course_id",
						  "fixed_price",
						  "discount",
						  "price",
						  "pay_type",
						  "payment",
						  "payment_cash",
						  "payment_card",
						  "payment_transfer",
						  "payment_loan",
						  "balance",
						  "memo",
						  "pay_date",
						  "edit_date");
	// 更新 OR 新規
	if($contract['sales_id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$contract['sales_id']);//再度精算、前回精算の取り消し
	else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	// 旧契約tableに反映---------------------------------------------------------------------------
	$_POST['new_course_id'] = $_POST['course_id'];
	if(!$_POST['if_cancel_date']) $_POST['if_cancel_date'] =0;
	$contract_field2 = array("new_contract_id",
						 	 "new_course_id",
						 	 "status",
						 	 "sales_id",
						 	 "cancel_date",
						 	 "if_cancel_date",
						 	 "conversion_flg",
						 	 "edit_date",
						 	 "memo");
	// 更新
	if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);

	// プラン変更適応日, 処理日含み
	if($_POST['if_cancel_date']){
		// 同時複数予約を考慮
		$GLOBALS['mysqldb']->query("UPDATE reservation SET contract_id=".$_POST['new_contract_id']." ,course_id=".$_POST['course_id'].",edit_date='".$_POST['edit_date']."'
					 WHERE  customer_id = '".$_POST['customer_id']."'
					 AND  contract_id = '".$_POST['contract_id']."'
					 AND  hope_date = '".$_POST['cancel_date']."'
					 ORDER BY reg_date desc LIMIT 1") OR die('query error'.$GLOBALS['mysqldb']->error);
	// 処理日以後
	}else{
		$GLOBALS['mysqldb']->query("UPDATE reservation SET contract_id=".$_POST['old_contract_id']." ,course_id=".$_POST['old_course_id'].",edit_date='".$_POST['edit_date']."'
					 WHERE  customer_id = '".$_POST['customer_id']."'
					 AND  contract_id = '".$_POST['contract_id']."'
					 AND  hope_date = '".$_POST['cancel_date']."'
					 LIMIT 3") OR die('query error'.$GLOBALS['mysqldb']->error);
	}

	$GLOBALS['mysqldb']->query("UPDATE reservation SET contract_id=".$_POST['new_contract_id']." ,course_id=".$_POST['course_id'].",edit_date='".$_POST['edit_date']."'
				 WHERE  customer_id = '".$_POST['customer_id']."'
				 AND  contract_id = '".$_POST['contract_id']."'
				 AND  hope_date > '".$_POST['cancel_date']."'")
				 OR die('query error'.$GLOBALS['mysqldb']->error);

	// Msg------------------------------------------------------------------------------
	if( $_POST['contract_id'] && $_POST['sales_id'] ) {
		$gMsg = '（処理完了しました。）';
		$complete_flg = 1;
	}else           $gMsg = '（登録しませんでした。)';
  }
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['contract_id'] )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 AND id = '".addslashes($data['sales_id'])."'");

// プラン変更：status=0
}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0
								   AND (status=0 OR status=5 OR status=7 OR (status=4 AND old_contract_id='' AND new_contract_id<>''))
								   AND customer_id = '".($_POST['customer_id'])."'
								   ORDER BY FIELD(status,4,5,7,0 )");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 AND id = '".addslashes($data['sales_id'])."'");
}

if($data['new_contract_id']) {
	$new_contract = Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($data['new_contract_id'])."' ORDER BY id desc");
	// 変更後の契約内容取得
	$new_course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".$data['new_course_id']."'");
}

$course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($data['course_id'])."'");

// 消化単価
$per_price = $data['times'] ? round($data['price']/$data['times']) : 0;
if($course['type'] && $data['r_times']>2)$usered_price = $per_price * $course['times'];

// 消化金額
else $usered_price = $per_price * $data['r_times'];

// 解約の場合、支払済金額リセット
if($data['status']==2 || $data['status']==3 || $data['status']==5) $payed_price =0;

// 支払済金額
else $payed_price = $data['price'] - $data['balance'];

// 残金
$remained_price = $payed_price - $usered_price;

// 返金額
$return_price = $remained_price - $charge;

// 返金額,table登録用
$return_price0 = 0-$return_price;

// 店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist("shop");

// staff list
// if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) OR die('query error'.mysql_error());
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// tax
if(!empty($data['new_contract_id']) && $new_contract['reg_date']<"2014-04-01"){
    $tax = 0.05;
    $tax2 = 1.05;
}elseif(!empty($data['new_contract_id']) && $new_contract['reg_date']<"2019-10-01") {
    $tax = 0.08;
    $tax2 = 1.08;
}else{
    $tax_data = Get_Table_Row("basic"," WHERE id = 1");
    $tax =$tax_data['value'];
    $tax2 = 1+$tax_data['value'];
}



// 旧コースリスト
$today = strtotime(date("Y-m-d"));
if( $data['cancel_date']=="0000-00-00" || strtotime($data['cancel_date']) >= $today ){ //システム権限以外かつ変更日が今日から未来の場合、旧コースを表示する
	$old_course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 AND old_flg=0 AND id<=1000 order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);
}else{ //システム権限、過去日は旧コースを含め表示する
	$old_course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 AND id<=1000 order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);
}
$old_course_list[0] = "-";
while ( $old_course_result = $old_course_sql->fetch_assoc() ) {
    $old_course_list[$old_course_result['id']] = $old_course_result['name'];
}

// パックから月額への変更を不可にするために検索条件に現在の契約がパックの場合は検索しないようにする
if (!$data['new_contract_id']) {
	$course_change_type_cond = $course['type'] == 0 ? ' AND type = 0 ' : '';
}

// 新コースリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND  status=2 AND type !=2 AND treatment_type = 0 AND group_id != 17 AND (sales_end_date IS NULL OR sales_end_date >= CURDATE()) AND old_flg=0 AND id<=1000 " . $course_change_type_cond . " order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);

$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
$course_type[0] = "-";
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];// コース区分 0.パック、1.月額
	$course_new_flg[$result2['id']] = $result2['new_flg'];// 新月額フラグ 0.旧月額、1.新月額
	// 税込
	if ($result2['id'] == 97 && $tax == 0.1) {
		//平日とく得2年プランの消費税10%時の売価を281000に調整
		$course_price[$result2['id']] = 281000;
	} else {
		//税込
		$course_price[$result2['id']] = round(($result2['price'] * $tax2), 0);
	}
	$course_name[] = $result2['name'];
}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company","-","","rank");
$loan_company_id = $_POST['loan_company_id'] ? $_POST['loan_company_id'] : $new_contract['loan_company_id'] ;

// JSに渡すため、配列をJSON化----------------------------------------------------------------------------
$json_course_price = json_encode($course_price);
$json_course_list = json_encode($course_list);
$json_course_type = json_encode($course_type);

if($course_type[$data['course_id']] && $data['start_ym']<>0){
	// 施術開始年月
	$start_ym = $data['start_ym'].'01';
	// 契約日の月と施術開始年月の月が同じ場合の契約期間は契約日～とする 2017/03/07 add by shimada
	if($data['start_ym'] == date('Ym', strtotime($data['contract_date']))){
		// 契約日：契約日～
		$contract_date = $data['contract_date'];
	} else {
		// 契約日：施術開始年月の初日～
	$contract_date = date('Y-m-d', strtotime($start_ym.'-01'));
	}
	// 契約終了日　※日付6桁を8桁に変換し、施術開始予定(年月)の末日を取得する
	$end_date = date('Y-m-d', strtotime('+1 month last day of ' . $start_ym));
	$times = $data['times']; // 回数
	$remain_times = $times>$data['r_times'] ? ($times-$data['r_times']) : 0;

	// 新月額は回数1回として単価・割引後単価を表示させる
	if($course['new_flg']==1){
		$times = 1;// 回数
		$remain_times = $data['r_times'] ? 0 : 1;
		$per_fixed_price = round($data['fixed_price']);
		$per_price = round($data['fixed_price']-$data['discount']);
		if($data['discount'])$per_discount = round($data['discount']);
	}
} else {
	// 契約開始日
	$contract_date = $data['contract_date'];
	// パック契約の方は契約データから終了日を取得する
	$end_date = $data['end_date'];
	// パックの回数
	$times = $data['times'];
	$remain_times = $times>$data['r_times'] ? ($times-$data['r_times']) : 0;
}

if($new_contract['times']) {
	$new_per_price = round(($new_contract['fixed_price']-$new_contract['discount'])/$new_contract['times']);
	$new_usered_price = $new_per_price * $data['r_times'];
	$new_remained_price = ($new_contract['fixed_price']-$new_contract['discount']) - $new_usered_price;
}

// 全角から半角へ
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

// 通知書用のパラメータ
$pdf_param  = "?shop_name=".$shop['name'].
			  "&shop_zip=".$shop['zip'].
			  "&shop_pref=".$gPref[$shop['pref']].
			  "&shop_address1=".$shop_address1.
			  "&shop_address2=".$shop_address2.
			  "&shop_tel=".$shop['tel'].
			  "&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] ).
			  "&no=".$customer['no'].
			  "&name_kana=".$customer['name_kana'].
			  "&birthday=".$customer['birthday'].
			  "&address=".$customer['address'].
			  "&tel=".$customer['tel'].
			  "&cancel_date=".$data['cancel_date'].
			  "&old_course_name=".$old_course_list[$data['course_id']].
			  "&fixed_price=".$data['fixed_price'].
			  "&discount=".$data['discount'].
			  "&per_price=".$per_price.
			  "&payed_price=".$payed_price.
			  "&times=".$times.
			  "&r_times=".$data['r_times'].
			  "&remain_times=".$remain_times.
			  "&contract_date=".$contract_date.
			  "&end_date=".$end_date.
			  "&new_course_name=".$course_list[$data['new_course_id']].
			  "&new_fixed_price=".$new_contract['fixed_price'].
			  "&new_discount=".$new_contract['discount'].
			  "&new_price=".$new_contract['price'].
			  "&new_times=".$new_contract['times'].
			  "&new_per_price=".$new_per_price.
			  "&new_balance=".$new_contract['balance'].
			  "&new_contract_date=".$new_contract['contract_date'].
			  "&new_end_date=".$new_contract['end_date'].
			  "&new_payment=".$new_contract['payment'].
			  "&new_payment_cash=".$new_contract['payment_cash'].
			  "&new_payment_card=".$new_contract['payment_card'].
			  "&new_payment_transfer=".$new_contract['payment_transfer'].
			  "&new_payment_loan=".$new_contract['payment_loan'].
			  "&staff=".$staff_list[$new_contract['staff_id']].
			  "&option_name=".$new_contract['option_name'];

// 領収書用のパラメータ
$pdf_param3  = "?shop_name=".$shop['name'].
			  "&shop_zip=".$shop['zip'].
			  "&shop_pref=".$gPref[$shop['pref']].
			  "&shop_address1=".$shop_address1.
			  "&shop_address2=".$shop_address2.
			  "&shop_tel=".$shop['tel'].
			  "&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] ).
			  "&old_course_name=".$old_course_list[$data['course_id']].
			  "&fixed_price=".$data['fixed_price'].
			  "&discount=".$data['discount'].
			  "&price=".$data['price'].
			  "&payed_price=".$payed_price.
			  "&r_times=".$data['r_times'].
			  "&new_course_name=".$course_list[$data['new_course_id']].
			  "&new_fixed_price=".$new_contract['fixed_price'].
			  "&new_price=".$new_contract['price'].
			  "&payment=".$new_contract['payment'].
			  "&balance=".$new_contract['balance'].
			  "&per_price=".$new_per_price.
			  "&usered_price=".$new_usered_price.
			  "&remained_price=".$new_remained_price.
              "&title=組替";

if(isset($new_contract['fixed_price']) && isset($new_contract['price']) && isset($new_contract['payment']) && isset($new_contract['balance'])){
	$receipt_flg = 1;
}
