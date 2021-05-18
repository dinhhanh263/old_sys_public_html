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
// ini_set("display_errors", 1);
// error_reporting(E_ALL);

// reservation.idが基準----------------------------------------------------------------------------

// 契約時点のレジ精算と残金のレジ精算
// 精算した金額、コース名を売上テーブルsalesに格納
// 売上合計、分析の際、どのテーブルからデータ取得？
// mysqlの高速化とtable数。ただ、(カウンセリング予約数+施術予約数＞レジ精算数)ので、salesテーブルが必要

// 既存会員の契約新規処理？
// customer.reg_flg=0;契約新規時、レジ精算前
// customer.reg_flg=1;契約新規時、レジ精算済（初回支払）

// レジ担当=>reservation?sales?

// 契約：初回新規、プラン変更より新規、全契約期間終了より新規、該当契約の役務消化（残る回数、売掛金）、キャンセル、返金
// 契約期間：二年

// １．初回新規契約と初回新規契約の変更
// ２．区分により施術精算の売掛金、役務消化回数の計上
// ３．値引き：数字　と　%

$table = "reservation";

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	// 無料プラン付与
	if ($_POST['free_flg']) {

		// 付与元の契約情報を取得
		$base_contract = Get_Table_Row("contract", " WHERE del_flg=0 and id = '".addslashes($_POST['base_contract_id'])."'");
		$base_course = Get_Table_Row("course", " WHERE del_flg=0 and id = '".addslashes($base_contract['course_id'])."'");

		// 付与可能期間を算出
		$base_end_date = !is_null($base_contract['extension_end_date']) ? $base_contract['extension_end_date'] : $base_contract['end_date']; // 付与元の回数保証期間終了日
		$grant_base_date = ($base_contract['latest_date'] != "0000-00-00" && $base_contract['latest_date'] < $base_end_date && $base_contract['status'] != 9) ? $base_contract['latest_date'] : $base_end_date; // 付与基準日(最終消化日or回数保証期間終了日の早い方)
		if ($base_contract['option_contract_id']) $contract = Get_Table_Row("contract", " WHERE del_flg=0 and id = '".addslashes($base_contract['option_contract_id'])."'");
		$course = Get_Table_Row("course", " WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");
		$_POST['end_date'] = date("Y-m-d", strtotime("+".$course['period']." day", strtotime($grant_base_date))); // 付与可能期間

		// 最終仕上げプランかどうか判別
		$finish_flg = false;
		if ($base_course['group_id'] == 17) $finish_flg = true;
		elseif (!is_null($base_course['old_course_id'])) {
			$old_course = Get_Table_Row("course", " WHERE del_flg=0 and id = '".addslashes($base_course['old_course_id'])."'"); // 返金保証回数終了プランの場合、移行前のコース情報を取得
			if ($old_course['group_id'] == 17) $finish_flg = true;
		}

		$editable_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-0, date("Y")));
		$editable_date2 = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d")-0, date("Y")));

		if (!$_POST['base_contract_id'] || !$base_contract['id']) {
			$gMsg = "<font color='red' size='-1'>※付与元の契約が存在しません。</font>";
		} elseif (!$_POST['course_id']) {
			$gMsg = "<font color='red' size='-1'>※コースを選択してください。</font>";
		} elseif ($authority_level > 6 && $contract['id'] && $contract['contract_date'] < $editable_date) {
			$gMsg = "<font color='red' size='-1'>※本日前のデータが編集不可です。</font>";
		} elseif ($authority_level > 0 && $authority_level <= 6 && $contract['id'] && $contract['contract_date'] < $editable_date2) {
			$gMsg = "<font color='red' size='-1'>※一ヶ月前のデータが編集不可です。</font>";
		} elseif ($base_course['type'] != 0 || $base_course['group_id'] == 11 || $base_course['group_id'] == 80 || $base_course['period'] == 0 || $base_course['zero_flg'] == 1 || $base_course['treatment_type'] != 0 || $finish_flg) {
			$gMsg = "<font color='red' size='-1'>※無料プラン付与対象外の契約です。</font>";
		} elseif ($base_contract['status'] != 0 && $base_contract['status'] != 1 && $base_contract['status'] != 9) {
			$gMsg = "<font color='red' size='-1'>※無料プランを付与できないステータスです。</font>";
		} elseif ($base_contract['r_times'] < $base_contract['times'] && ($base_contract['status'] != 9 || $base_course['id'] <= 1000)) {
			$gMsg = "<font color='red' size='-1'>※全消化されていないため無料プランを付与できません。</font>";
		} elseif ($_POST['end_date'] < date("Y-m-d")) {
			$gMsg = "<font color='red' size='-1'>※付与可能期間を過ぎているため無料プランを付与できません。</font>";
		} elseif (!$_POST['option_name']) {
			$gMsg = "<font color='red' size='-1'>※オプションを選択してください。</font>";
		} elseif ($authority_level > 0 && !$_POST['cstaff_id']) {
			$gMsg = "<font color='red' size='-1'>※処理者を選択してください。</font>";
		} else {

			// POSTに格納
			$_POST['staff_id'] = $_POST['cstaff_id'];
			$_POST['times'] = $course['times'];
			$_POST['contract_date'] = $contract['contract_date'] ? $contract['contract_date'] : date("Y-m-d");
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

			// 契約tableに反映
			$contract_field  = array( // 新規
				"customer_id",
				"shop_id",
				"staff_id",
				"option_name",
				"course_id",
				"times",
				"contract_date",
				"end_date",
				"memo",
				"reg_date",
				"edit_date",
			);
			$contract_field2 = array( // 更新
				"shop_id",
				"staff_id",
				"option_name",
				"course_id",
				"times",
				"contract_date",
				"end_date",
				"memo",
				"edit_date",
			);

			if (!$contract['id']) $_POST['option_contract_id'] = Input_New_Data("contract", $contract_field); // 新規
			else $_POST['option_contract_id'] = Update_Data("contract", $contract_field2, $contract['id']); // 更新

			// 新規の場合、付与元のoption_contract_idを更新
			if ($_POST['option_contract_id'] != $base_contract['option_contract_id']) {
				$base_contract_field = array(
					"edit_date",
					"option_contract_id",
				);
				$data_ID = Update_Data("contract", $base_contract_field, $base_contract['id']);
			}
			$gMsg = ($_POST['option_contract_id']) ? "（登録完了）" : "（登録しませんでした。)";
		}

	// 通常のレジ精算
	} else {

	if( $_POST['reservation_id']) $reservation = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
	if( $reservation['contract_id']) $contract_date = Get_Table_Col("contract","contract_date"," WHERE del_flg=0 and id = '".addslashes($reservation['contract_id'])."'");
		$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");

	$editable_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-0, date("Y")));
	$editable_date2 = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	$today = strtotime(date("Y-m-d"));
	$customer = Get_Table_Row("customer", " WHERE del_flg=0 and id = '" . addslashes($reservation['customer_id']) . "'");

	//未成年プランで契約できる日付の取得
	$twenty_birthday = strtotime('+20 year', strtotime($customer['birthday']));
	$minor_plan_end_date = strtotime($minor_plan_end_days, $twenty_birthday);

	if(!$_POST['reservation_id']){
		$gMsg = "<font color='red' size='-1'>※予約自体が未登録です。</font>";

	}elseif($authority_level>6 && $reservation['hope_date']<$editable_date){
		$gMsg = "<font color='red' size='-1'>※本日前のデータが編集不可です。</font>";

	}elseif($authority_level>=1 && $authority_level<=6 && $reservation['hope_date']<$editable_date2){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";

	}elseif($reservation['contract_id'] && $reservation['course_id'] && $reservation['hope_date']<>$contract_date && $_POST['course_id']<>$reservation['course_id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※レジ精算でプラン変更できません。</span>";

	}elseif($authority_level>=1 && $reservation['contract_id'] && $reservation['hope_date']<>$contract_date ){
		$gMsg = "<span style='color:red;font-size:13px;'>※新規契約予約よりレジ精算してください。</span>";

	}elseif(!$reservation['contract_id'] && ($existed_cid = Get_Table_Col("contract","id"," WHERE del_flg=0 AND reservation_id ='".$_POST['reservation_id']."'" ) ) ){
		$gMsg = "<span style='color:red;font-size:13px;'>※二重レジ清算でした。</span>";

	}elseif($reservation['hope_date']>date('Y-m-d')){
		$gMsg = "<font color='red' size='-1'>※未来日にレジ精算できません。</font>";

	}elseif($authority_level>0 && $reservation['type'] != 33 && $customer['birthday'] == "0000-00-00"){
		$gMsg = "<font color='red' size='-1'>※生年月日が登録されていないためレジ精算できません。</font>";

	}elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && !$_POST['loan_company_id']){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※ローン会社を選択してください。</span>";

	}elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && $_POST['payment_loan']%10000<>0){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※ローン金額が正しくありません。</span>";

	}elseif($authority_level>1 && !$_POST['cstaff_id']){
		if($_POST['type']==32) $gMsg = "<font color='red' size='-1'>※ミドルカウンセリング担当を選択してください。</font>";
		else $gMsg = "<font color='red' size='-1'>※カウンセリング担当を選択してください。</font>";

	}elseif($authority_level>6 && !$_POST['rstaff_id']){
		$gMsg = "<font color='red' size='-1'>※レジ担当を選択してください。</font>";

	}elseif($course['new_flg'] && !$_POST['start_ym']){
		$gMsg = "<font color='red' size='-1'>※「施術開始年月」を選択してください。</font>";
	}elseif($course['minor_plan_flg'] == 1 && $today > $minor_plan_end_date){
		$gMsg = "<font color='red' size='-1'>未成年プランを契約できる生年月日ではありません。</font>";
	// コース未選択確認----------------
	}elseif(!$_POST['fixed_price']){

		// データ取得--------------------------------------------------------------------------------

		if($reservation['reg_flg'] ){

			$sql = "UPDATE ".$table." SET reg_flg = 0,course_id=0,contract_id=0,r_times=0,sales_id=0";
			$sql .= " WHERE id = '".addslashes($reservation['id'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

			// 契約データ仮削除
			$sql = "UPDATE contract SET del_flg = 1";
			$sql .= " WHERE customer_id = '".addslashes($reservation['customer_id'])."' and id = '".addslashes($reservation['contract_id'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

			// 売上データ仮削除
			$sql = "UPDATE sales SET del_flg = 1";
			$sql .= " WHERE id = '".addslashes($reservation['sales_id'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

			$gMsg = "<font color='red' size='-1'>※削除が完了しました。</font>";

		}else{
			$gMsg = "<font color='red' size='-1'>※コースが未選択でした。</font>";
		}
	}else{

		// データ取得--------------------------------------------------------------------------------

		if( $_POST['course_id'] )  	 $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");

		// 契約詳細取得-----------------------------------------------------------------------------
		if($reservation['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($reservation['contract_id'])."'");
		// else $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and customer_id = '".addslashes($reservation['customer_id'] )."' and del_flg=0 and (status=0 or status=7) and end_date>='".date("Y-m-d")."'");

		// POSTに格納------------------------------------------------------------------------------
		$_POST['contract_date'] = $_POST['pay_date'] = $reservation['hope_date'];
		$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card']  + $_POST['payment_transfer'] + $_POST['payment_loan'] + $_POST['payment_coupon']; //支払金額＝現金+カード+振込+ローン+クーポン
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		// $_POST['reservation_id'] = $_POST['id'];
		$_POST['times'] = $course['times'];
		$_POST['reg_flg'] = 1;
		$_POST['staff_id'] = $_POST['cstaff_id'] ? $_POST['cstaff_id'] : $_POST['tstaff_id'];
		if($course['period']) $_POST['end_date'] = date("Y-m-d",strtotime("+{$course['period']} day",strtotime($_POST['contract_date'])));

		//未成年プランの最終消化タームの計算
		if($course['minor_plan_flg'] == 1 && $course['type'] == 1) {

			$birthday_month = strtotime(substr($customer['birthday'], 0, 7));
			$twenty_birthday_month = strtotime('+20 year', $birthday_month);

			$check_start_month = (int)substr($_POST['start_ym'], -2) % 2;
			$check_birthday_month = (int)substr($customer['birthday'], 5, 2) % 2;

			//開始月と誕生日の奇数偶数が一致していたら誕生日月の翌月末の日をセット、一致しない場合は誕生日月の月末をセット
			if($check_start_month === $check_birthday_month) {
				$_POST['end_date'] = date("Y-m-d", strtotime('+2 month -1 day', $twenty_birthday_month));
			} else {
				$_POST['end_date'] = date("Y-m-d", strtotime('+1 month -1 day', $twenty_birthday_month));
			}
		}

		// 売掛金
		$_POST['balance'] = $_POST['price'] - $_POST['payment'];
		// パックの時は施術開始年月を初期化する
		if($course['type']==0 && $_POST['start_ym']<>0){
			$_POST['start_ym'] = 0;
		}

		// 契約tableに反映------------------------------------------------------------------------
		// 新規のみ、他の日で残金回収の可能性があり、編集の場合上書き防止
		if($_POST['balance']<=0 && !$_POST['payment_loan']) $_POST['pay_complete_date']=$_POST['contract_date'];

		// 「支払完了日」記入有、掛けが0円以下、ローン承認済み　の場合、支払完了日を予約日で上書きする 20151001 shimada
		 // *1回で掛け清算時*
		else if($contract['pay_complete_date'] != "0000-00-00" && $_POST['balance']<=0 && $contract['loan_status'] ==1) $_POST['pay_complete_date']=$reservation['hope_date'];

		// *複数回で掛け清算時*
		else if($contract['pay_complete_date'] != "0000-00-00" && ($contract['balance'] - $_POST['balance'])<=0 && $contract['loan_status'] ==1) $_POST['pay_complete_date']=$reservation['hope_date'];
		else $_POST['pay_complete_date'] = "0000-00-00";

		// ローン申込日記入 add by ka 20180629
		if($_POST['payment_loan']) $_POST['loan_application_date']=$_POST['contract_date'];
		else $_POST['loan_application_date'] = "0000-00-00";

		$contract_field  = array("reservation_id",
								 "customer_id",
								 "shop_id",
								 "staff_id",
								 "option_name",
								 "course_id",
								 "times",
								 "fixed_price",
								 "discount",
								 "dis_type",
								 "price",
								 "pay_type",
								 "pay_complete_date",
								 "payment",
								 "payment_cash",
								 "payment_card",
								 "payment_transfer",
								 "payment_loan",
								 "loan_application_date",
								 "payment_coupon",
								 "start_ym",
								 "balance",
								 "memo",
								 "contract_date",
								 "end_date",
								 "reg_date",
								 "edit_date");
		$contract_field2 = array("shop_id",
								 "staff_id",
								 "option_name",
								 "course_id",
								 "times",
								 "fixed_price",
								 "discount",
								 "dis_type",
								 "price",
								 "pay_type",
								 "pay_complete_date",
								 "payment",
								 "payment_cash",
								 "payment_card",
								 "payment_transfer",
								 "payment_loan",
								 "loan_application_date",
								 "payment_coupon",
								 "start_ym",
								 "balance",
								 "memo",
								 "contract_date",
								 "end_date",
								 "edit_date",
								 "extension_end_date");

		// ローン不備チェック判定（チェック入りだった場合のみ 5.ローン不備=>3.承認中に変更する 20160831 add by shimada
		if($_POST['if_loan_deficiency'] && $contract['loan_status']==5){
			$_POST['loan_status']=3;
			array_push($contract_field2,  "loan_status");
		}

		if( is_numeric($_POST['payment_loan']) || $_POST['payment_loan']>0){
			array_push($contract_field , "loan_company_id");
			array_push($contract_field2 , "loan_company_id");
		}
		// 入力されたローンの取消処理
		if( $contract['payment_loan'] && !$_POST['payment_loan']){
			$_POST['loan_company_id'] = 0;
			array_push($contract_field2 , "loan_company_id");
		}

		// 11/01以降の新コースで猶予期間設定
		if (!empty($course['extension_period'])) {
		    $_POST['extension_end_date'] = date("Y-m-d",strtotime("+{$course['period']} day + {$course['extension_period']} day",strtotime($_POST['contract_date'])));
		    array_push($contract_field , "extension_end_date");
		    // array_push($contract_field2 , "extension_end_date");
		} else {
				$_POST['extension_end_date'] = "NULL"; // レジ精算後に新パック→月額に修正した場合、extension_end_dateをNULLに戻す
		}

		// 更新 or 新規
		if($_POST['type'] != 33) {
			if($reservation['contract_id']) $_POST['contract_id'] = Update_Data_Null("contract",$contract_field2,$reservation['contract_id']);
			// 新規
			else $_POST['contract_id'] = Input_New_Data("contract",$contract_field);
		}
		// 新規の場合、スマートピット番号付与
		/*if(!$reservation['contract_id']){
			$smartpit_id = Get_Table_Col("smartpit","min(id)"," WHERE del_flg=0 AND give_flg=0");
			// 付与済処理
			$GLOBALS['mysqldb']->query( "UPDATE smartpit SET give_flg=1,give_date=now() WHERE id=".$smartpit_id ) or die('query error'.mysql_error());
			// 番号付与
			$GLOBALS['mysqldb']->query( "UPDATE customer SET smartpit_id=".$smartpit_id." WHERE id=".$reservation['customer_id'] ) or die('query error'.mysql_error());
		}*/

		// 売上tableに反映------------------------------------------------------------------------

		$sales_field  = array("contract_id",
							  "type",
							  "reservation_id",
							  "customer_id",
							  "shop_id",
							  "staff_id",
							  "rstaff_id",
							  "course_id",
							  "times",
							  "fixed_price",
							  "discount",
							  "dis_type",
							  "point",
							  "option_name",
							  "option_price",
							  "price",
							  "pay_type",
							  "payment",
							  "payment_cash",
							  "payment_card",
							  "payment_transfer",
							  "payment_loan",
							  "payment_coupon",
							  "balance",
							  "memo",
							  "pay_date",
							  "reg_date",
							  "edit_date",
							  "non_record_flg");
		$sales_field2 = array("shop_id",
							  "staff_id",
							  "rstaff_id",
							  "course_id",
							  "times",
							  "fixed_price",
							  "discount",
							  "dis_type",
							  "point",
							  "option_name",
							  "option_price",
							  "price",
							  "pay_type",
							  "payment",
							  "payment_cash",
							  "payment_card",
							  "payment_transfer",
							  "payment_loan",
							  "payment_coupon",
							  "balance",
							  "memo",
							  "pay_date",
							  "edit_date",
							  "non_record_flg");

		// 更新 or 新規

		// 再度精算、前回精算の取り消し
		if($reservation['sales_id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$reservation['sales_id']);

		// 売上計上（新規）
		else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);

		// 予約tableに反映-------------------------------------------------------------------------

		$reservation_field = array("contract_id","sales_id","course_id","reg_flg","edit_date","rstaff_id","edit_date");
		if(isset($_POST['cstaff_id'])) array_push($reservation_field,  "cstaff_id");
		if(isset($_POST['tstaff_id'])) array_push($reservation_field,  "tstaff_id", "tstaff_sub1_id", "tstaff_sub2_id");

		// 更新
		$data_ID = Update_Data($table ,$reservation_field,$_POST['reservation_id']);

		// Msg----------------------------------------------------------------------------

		if( $data_ID ) {
			$gMsg = '（登録完了）';
			$complete_flg = 1;
		}else $gMsg = '（登録しませんでした。)';
	}
	}
}

// 詳細を取得----------------------------------------------------------------------------

$default_course_id = 0; // 初期表示コースID
// 無料プラン付与
if ($_POST['free_flg'] && $_POST['base_contract_id']) {
	$base_contract = Get_Table_Row("contract", " WHERE del_flg=0 and id = '".addslashes($_POST['base_contract_id'])."'");
	$customer = Get_Table_Row("customer", " WHERE del_flg=0 and id = '".addslashes($base_contract['customer_id'])."'");
	$shop = Get_Table_Row("shop", " WHERE del_flg=0 and id = '".addslashes($base_contract['shop_id'])."'");
	if($base_contract['option_contract_id']) $contract = Get_Table_Row("contract", " WHERE del_flg=0 and id = '".addslashes($base_contract['option_contract_id'])."'");

	// data, salesに格納(初期表示設定)
	$data = $sales = array();
	$data['shop_id'] = $base_contract['shop_id'];
	$data['type'] = 11; // 区分はその他を仮指定
	$data['hope_date'] = $contract['contract_date'] ? $contract['contract_date'] : date("Y-m-d");
	$sales['course_id'] = $contract['course_id'];

	// 初期表示させる無料プランを取得
	$default_course_id = Get_Table_Col("course","id"," WHERE del_flg=0 AND id>2000 AND id <=3000 AND group_id=80 ORDER BY id asc LIMIT 1");

// 通常のレジ精算
} elseif( $_POST['reservation_id'] ) {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
	if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
}

// 店舗リスト----------------------------------------------------------------------------

$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//staff list
if($contract['id'])$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$contract['contract_date']."')".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
else $staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax
if($data['hope_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}else if($data['hope_date']<"2019-10-01") {
    $tax = 0.08;
    $tax2 = 1.08;
}else{
	$tax_data = Get_Table_Row("basic"," WHERE id = 1");
	$tax =$tax_data['value'];
	$tax =$tax_data['value'];
	$tax2 = 1+$tax_data['value'];
}

// courseリスト
$today = strtotime(date("Y-m-d"));
//無料プラン付与
if ($base_contract['id']) {
	// 無料プラン(course.id>2000)を表示
	if (!$contract['id']) { // 無料プランを付与していない場合、旧コースを表示しない
			$course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND (sales_end_date IS NULL OR sales_end_date >= CURDATE()) AND old_flg=0 AND id>2000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
	} else {
			$course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND (sales_end_date IS NULL OR sales_end_date >= '" . $contract['contract_date'] . "') AND id>2000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
	}
//ショット
} else if ($data['type'] == 33) {
    //ショットコースは区分でコース出しわけ
    if (strtotime($data['hope_date']) >= $today) { //システム権限以外かつ予約日が今日から未来の場合、旧コースを表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type =2 AND (sales_end_date IS NULL OR sales_end_date >= CURDATE()) AND old_flg=0 AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    } else { //システム権限、過去日は旧コースを含め表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type =2 AND (sales_end_date IS NULL OR sales_end_date >= '" . $data['hope_date'] . "') AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    }
//追加契約
} else if ($data['type'] == 32) {
	if (strtotime($data['hope_date']) >= $today) { //システム権限以外かつ予約日が今日から未来の場合、旧コースを表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND (treatment_type = 1 OR treatment_type = 2 OR group_id = 17) AND (sales_end_date IS NULL OR sales_end_date >= CURDATE()) AND old_flg=0 AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    } else { //システム権限、過去日は旧コースを含め表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND (treatment_type = 1 OR treatment_type = 2 OR group_id = 17) AND (sales_end_date IS NULL OR sales_end_date >= '" . $data['hope_date'] . "') AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    }
} else {
    if (strtotime($data['hope_date']) >= $today) { //システム権限以外かつ予約日が今日から未来の場合、旧コースを表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND treatment_type = 0 AND group_id != 17 AND (sales_end_date IS NULL OR sales_end_date >= CURDATE()) AND old_flg=0 AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    } else { //システム権限、過去日は旧コースを含め表示する
        $course_sql = $GLOBALS['mysqldb']->query("select * from course WHERE del_flg = 0 AND status=2 AND type !=2 AND treatment_type = 0 AND group_id != 17 AND (sales_end_date IS NULL OR sales_end_date >= '" . $data['hope_date'] . "') AND id<=1000 order by group_id,name") or die('query error' . $GLOBALS['mysqldb']->error);
    }
}
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
$course_type[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type']; // コースタイプ 0.パック、1.月額 20161013 shimada追加
	$treatment_type[$result['id']] = $result['treatment_type'];

	$tax_included_price = round(($result['price'] * $tax2),0);

	// 新月額2ヶ月コースでcourseテーブルの税抜き価格に10%をかけた場合、奇数になってしまうため、1ヶ月分の支払金額が1円ずれてしまう問題の暫定対応
	// 月額で税込価格が奇数の場合、1円足す
	#if ($result['type'] == 1 && $result['sales_end_date'] < date("Y-m-d") && $tax_included_price %2 != 0) {
	if ($result['type'] == 1 && $tax_included_price %2 != 0) {
        //税込
		$course_price[$result['id']] = $tax_included_price + 1;
	} else if($result['id'] == 97 && $tax == 0.1) {
		//平日とく得2年プランの消費税10%時の売価を281000に調整
		$course_price[$result['id']] = 281000;
    } else {
	    //税込
	    $course_price[$result['id']] = $tax_included_price;
	}

	$course_name[] = $result['name'];

}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company","-","","rank");
$loan_company_id = $_POST['loan_company_id'] ? $_POST['loan_company_id'] : $contract['loan_company_id'] ;

// JSに渡すため、配列をJSON化----------------------------------------------------------------------------
$json_course_price = json_encode($course_price);
$json_course_list = json_encode($course_list);
$json_course_type = json_encode($course_type);

// 全角から半角へ
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

$pdf_param = "?shop_name=".$shop['name'].
			  "&shop_zip=".$shop['zip'].
			  "&shop_pref=".$gPref[$shop['pref']].
			  "&shop_address1=".$shop_address1.
			  "&shop_address2=".$shop_address2.
			  "&shop_tel=".$shop['tel'].
			  "&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] ).
			  "&course_name=".$course_list[$sales['course_id']].
			  "&tax=".$tax.
			  "&tax2=".$tax2.
			  "&fixed_price=".$sales['fixed_price'].
			  "&discount=".$sales['discount'].
			  "&price=".$sales['price'].
			  "&payment=".$sales['payment'].
			  "&payment_loan=".$sales['payment_loan'].
			  "&payment_cash=".$sales['payment_cash'].
			  "&payment_card=".$sales['payment_card'].
			  "&payment_transfer=".$sales['payment_transfer'].
			  "&option_name=".($sales['option_name'] ? $gOption[$sales['option_name']] : "オプション").
			  "&option_price=".$sales['option_price'].
			  "&balance=".$sales['balance'].
			  "&pay_date=".$sales['pay_date'];

// ホットペッパー------------------------------------------------------------------------------------------

if($data['hp_flg']) $hp_price = 10450;
if($data['hp_flg']==2) $hp_discount = 730;

?>
