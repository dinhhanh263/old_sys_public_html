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


$table = "reservation";


// 編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	if ($_POST['reservation_id']) {
		$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
		if ($data['sales_id']) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
	} elseif ($_POST['sales_id']) {
		$sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($_POST['sales_id'])."'");
	}

	$editable_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-0, date("Y")));
	$editable_date2 = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	// 支払金額＝現金+カード+振込+ローン
	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer']  + $_POST['payment_loan'];

	// submit制御----------------------------------------------------------------
	// 未来のエラーメッセージ
	$option_message_time = '';

	// 月のエラーメッセージ
	$option_message_month = '';

	// 月のnull判定（0で未入力判定されてしまうため）
	$month_null_flg = ($_POST['option_month'] ===null || $_POST['option_month'] =='') ? true:false;

	// 月のデータ整形（エラーチェックの前に正しい形にする）、 数値であれば月を半角/先頭0なしに整形する
	$_POST['option_month'] = is_numeric(Che_Num3($_POST['option_month'])) ? Che_Num3($_POST['option_month']):$_POST['option_month'] ;

	// 半角カンマ以外の記号を半角カンマに整形する
	$_POST['option_month'] = Comma($_POST['option_month']);

	// 年と月の未来日チェックを行う
	if(is_numeric($_POST['option_month'])){
		$date_flg = checkTerm($_POST['option_year'], $_POST['option_month'],'',3,2,'m');
		if($date_flg ==='f'){
			$option_message_time = '2ヶ月以上未来に清算できません。';
		}
	}

	// 月のエラーチェック  カンマなしのとき数値チェック/ ありのときは数値チェックする
	if(strpos($_POST['option_month'],',') === false){
		if(!is_numeric($_POST['option_month'])){
			$option_message_month = '月は数値のみで入力してください。';
		}else{
			if($date_flg ==='m'){
				$option_message_month = '月は1～12で入力してください。';
			}
		}
	} else {
		// カンマごとに配列に入れる
		$option_months = explode(',',$_POST['option_month']);

		// カンマごとの数字月が 文字以外/正しい月 ではない場合はエラーメッセージを表示させる
		foreach ($option_months as $month) {
			//半角に整形する
			$month = Che_Num3($month);
			$date_flg = checkTerm($_POST['option_year'], $month,'',3,2,'m');

			// 月の形式チェック
			if($month === false){
				$option_message_month = '月(1～12)のカンマ区切りで入力してください。';
			} else {
				if($date_flg ==='m'){
					$option_message_month = '月は1～12で入力してください。';
				}
				$months[] = $month;
			}
			// 過去日未来日チェック
			if($date_flg ==='p'){
				$option_message_past = '3ヶ月以上過去日の清算ですが、よろしいでしょうか。';
			} elseif($date_flg ==='f'){
				$option_message_time = '2ヶ月以上未来日に清算できません。';
			}
		}
		// 月のエラーがない場合、チェック済みの配列をカンマ区切りの形式に戻す、カンマ区切りの月をソート
	    $result = sort($months, SORT_NUMERIC);
		$_POST['option_month'] = implode(',', $months);
	}

	// submit制御----------------------------------------------------------------
	if(!$data['id'] && !$_POST['contract_id'] && !$sales['id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※契約または予約自体が未登録です。</span>";

	// }elseif(!$_POST['type']) {
	}elseif(!$data['id'] && !($_POST['type'] == 8 || $_POST['type'] == 11)) {
		$gMsg = "<span style='color:red;font-size:13px;'>区分が正しくありません。</span>";

	}elseif($authority_level>6 && $_POST['pay_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※本日前のデータが編集不可です。</span>";

	}elseif($authority_level>=1 && $authority_level<=6 && $_POST['pay_date']<$editable_date2){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";

	}elseif($authority_level>=1 && ($_POST['type']==7 || $_POST['type']==8 || $_POST['type']==10 ) && $_POST['if_service']){
		$gMsg = "<span style='color:red;font-size:13px;'>※売掛回収やプラン変更で役務消化できません。</span>";

	}elseif($authority_level>6 && $_POST['payment'] && $_POST['price']<($_POST['payment'])){
		$gMsg = "<span style='color:red;font-size:13px;'>※支払金額が売掛金より大きいです。</span>";

	}elseif($authority_level>=1 &&
			$_POST['pay_date']>date('Y-m-d') &&
			($_POST['payment']<>0 || ($_POST['option_price'] + $_POST['option_transfer'] + $_POST['option_card'])<>0)){
		$gMsg = "<span style='color:red;font-size:13px;'>※未来日にレジ精算できません。</span>";

	}elseif($_POST['course_id'] &&
			$_POST['course_id']<=1000 &&
			$_POST['course_type']==0 &&
			$_POST['if_service'] &&
			$_POST['r_times'] >= $_POST['times'] &&
			$_POST['zero_flg']==0
			){ // course_type=0 回数制のときチェック
		$gMsg = "<span style='color:red;font-size:13px;'>※消化回数が超過です。</span>";

	}elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && !$_POST['loan_company_id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※ローン会社を選択してください。</span>";

	}elseif( is_numeric($_POST['payment_loan']) && $_POST['payment_loan']>0 && $_POST['payment_loan']%10000<>0){
		$gMsg = "<span style='color:red;font-size:13px;'>※ローン金額が正しくありません。</span>";

	}elseif($_POST['course_type'] &&
			$_POST['option_name']<=2 &&
			($_POST['option_price'] + $_POST['option_transfer'] + $_POST['option_card'])>5000){
		$gMsg = "<span style='color:red;font-size:13px;'>※オプション名を正しく選択してください。</span>";

	}elseif($_POST['option_name']==4 && $_POST['option_year']=='-'){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※何年分を選択してください。</span>";

	}elseif($_POST['option_name']==4 && $month_null_flg===true){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※何月分を入力してください。</span>".$_POST['option_month'];

	}elseif($_POST['option_name']==4 && $option_message_month<>''){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※".$option_message_month."</span>";

	}elseif($_POST['option_name']==4 && $option_message_time<>''){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※".$option_message_time."</span>";

	}elseif($_POST['option_name']==4 && !$_POST['option_date']){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※振替日を入力してください。</span>";


	}elseif($_POST['option_name']==2){
		$gMsg = "<span style='color:red;font-size:13px;'>※店舗移動費が選択できません。</span>";

	// ホットペッパー月額120分の初回を除外。course_id=70
	// 新月額は部位を選ばないケースもあるため除外 new_flg 0.旧月額、1.新月額
	// 条件：旧月額 && ホットペッパー以外 && 役務消化登録する際は「施術部位」を選択しないとアラート表示する 2016/10/18 add by shimada
	}elseif($_POST['course_type'] && $_POST['new_flg']==0 && !($_POST['course_id']==70 && ($_POST['r_times']==0 || $sales['r_times']==1) )
		&& ($_POST['if_service'] || $sales['r_times']) && !$_POST['part']){
		$gMsg = "<span style='color:red;font-size:13px;'>※「全身」以外の施術部位を選択してください。</span>";

	}elseif($authority_level>6 && !$_POST['rstaff_id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※レジ担当を選択してください。</span>";
	}elseif(!isset($_POST['if_service']) && ($_POST['type'] == 2 || $_POST['type'] == 14) && !$sales['r_times']) {
		$gMsg = "<span style='color:red;font-size:13px;'>※役務消化にチェックを入れてください。</span>";
	}else{
		// 契約詳細取得-----------------------------------------------------------------
		// contract_idが予約詳細から引き続く
		if($_POST['contract_id']) {
			$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id <>0  and id = '".addslashes($_POST['contract_id'])."'");

			// 当日ローン取消、支払方法変更での対応
			if($contract['new_contract_id'] && $contract['cancel_date'] == $_POST['pay_date']){
				$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id <>0  and id = '".addslashes($contract['id'])."'");
				$_POST['contract_id'] = $contract['id'];
			}
		}
		// courseデータ取得----------------------------------------------------------------
		if( $_POST['course_id'] != "" ) $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");

		// 売上tableに反映----------------------------------------------------------
		// コース変更、途中解約を考慮し、現情報をテーブルに
		// $_POST['reservation_id'] = $_POST['reservation_id'];
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		$_POST['staff_id'] = $_POST['cstaff_id'] ? $_POST['cstaff_id'] : $_POST['tstaff_id'];

		// 予約なしの月額支払の場合、pay_dateを振替日に合わせる
		if (!$data['id'] && $_POST['type'] == 8 && $_POST['option_name'] == 4) $_POST['pay_date'] = $_POST['option_date'];

		// 売掛金
		//　売掛金の項目を表示しない予約区分の時は更新しない
		if(isset($_POST['price'])){
			$_POST['balance'] = $_POST['price'] - $_POST['payment'];
		} else {
			$_POST['balance'] = $contract['balance'];
		}
		// 消化回数計上
		$_POST['r_times'] = $_POST['r_times'] + 1;

		// 新規のみ、基本情報登録
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
							  "option_name",
							  "option_price",
							  "option_transfer",
							  "option_card",
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
							  "edit_date",
							  "non_record_flg");
		$sales_field2 = array("type",
							  "shop_id",
							  "staff_id",
							  "rstaff_id",
							  "times",
							  "option_name",
							  "option_price",
							  "option_transfer",
							  "option_card",
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
							  "edit_date",
							  "non_record_flg");
		if($_POST['if_service'] ) {
			array_push($sales_field , "r_times");
			array_push($sales_field2 ,"r_times");
		}
		if($_POST['option_name'] == 4) {
			array_push($sales_field , "option_year");
			array_push($sales_field2 ,"option_year");
			array_push($sales_field , "option_month");
			array_push($sales_field2 ,"option_month");

			array_push($sales_field , "option_date");
			array_push($sales_field2 , "option_date");
		}

		// 更新 or 新規
		// 再度精算、前回精算の取り消し,編集の場合、消化回数が計上しない
		if($sales['id']){
				$_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
		// 売上計上（新規）
		} else {
		    // この予約のsalesレコード
		    // $_POST['sales_id'] = Get_Table_Col("sales","id"," WHERE del_flg=0 and customer_id <>0  and reservation_id = '".addslashes($data['id'])."'");
		    // この予約のsalesレコードがない場合のみ、レコードの新規登録を行う
		    // if(!$_POST['sales_id']){
		        $_POST['sales_id'] = Input_New_Data("sales",$sales_field);
		    // }
		}

		// 消化回数、売掛金、最新来店日を契約tableに反映-------------------------------
		$_POST['latest_date'] = $_POST['pay_date'];

		$contract_field2 = array("balance","edit_date");

		// 注意：売上回収時最新来店日ではなく、予約日
		// if($_POST['payment'] && !$_POST['balance']) $_POST['pay_complete_date']= $_POST['pay_date'];
		/*	ローン承認中の掛回収、支払完了日に記入しない
			ローン承認済み後の掛回収、支払完了日を記入
			掛回収にローンがある、支払完了日に記入しない
		*/
		if( $_POST['payment']>0 &&
			( !($contract['payment_loan'] && $contract['loan_status']<>1) || $contract['loan_status']==1 ) && !$_POST['payment_loan'] && $_POST['balance']<=0 ){
			$_POST['pay_complete_date']= $_POST['pay_date'];//
			array_push($contract_field2 , "pay_complete_date");
 		}
 		// else $_POST['pay_complete_date'] = "0000-00-00";

 		// ローン支払合計と残掛け金　- ローンの掛け金（修正した金額）が0円超えたら、支払完了日を削除する 20151001 shimada
 		if($contract['balance'] > 0){
 			// ローン支払合計と残掛け金　- ローンの掛け金（修正した金額）が0円超えたら、支払完了日を削除する 20151001 shimada
			if(($contract['payment_loan'] + $contract['balance'] - $_POST['payment_loan'] > 0) && $_POST['balance']<>0){
			array_push($contract_field2 , "pay_complete_date");
			$_POST['pay_complete_date'] = "0000-00-00";
		}
 		}
		// 初回のみ、消化回数計上（同じ日重複計上しない,前提は古いデータは編集禁止）
		// if($_POST['if_service'] && !$data['sales_id']) array_push($contract_field2 , "r_times");

		if($_POST['if_service'] ) array_push($contract_field2 , "r_times");

		// 最終消化日
		if($_POST['type'] == 2 || $_POST['type'] == 14) array_push($contract_field2 , "latest_date");

		// 掛回収、二回目後のsubmit制御のため、初回のみ
		if(!$data['sales_id'] && $contract['balance']>0){
			$_POST['payment_cash'] 		= $contract['payment_cash'] 	+ $_POST['payment_cash'];
			$_POST['payment_transfer'] 	= $contract['payment_transfer'] + $_POST['payment_transfer'];
			$_POST['payment_card'] 		= $contract['payment_card'] 	+ $_POST['payment_card'];
			array_push($contract_field2 , "payment_cash", "payment_transfer", "payment_card");
		}

		// ローン一覧に表示できるため、取り敢えず、ローンの金額を入れる。（ローンフラッグを追加した方がいい？なら、ローン金額が取得できない）
		if($_POST['payment_loan'] ) array_push($contract_field2 , "payment_loan");
		if( is_numeric($_POST['payment_loan']) || $_POST['payment_loan']>0){
			array_push($contract_field2 , "loan_company_id");
		}
		// 入力されたローンの取消処理
		if( $sales['payment_loan'] && !$_POST['payment_loan']){
			$_POST['loan_company_id'] = 0;
			array_push($contract_field2 , "loan_company_id");
		}

		// ローン申込日記入 add by ka 20180629
		if($_POST['payment_loan'] && !$contract['payment_loan']){
			$_POST['loan_application_date']=$_POST['pay_date'];
			array_push($contract_field2 , "loan_application_date");
		}elseif(!$_POST['payment_loan'] && $sales['payment_loan'] && $contract['loan_application_date']==$_POST['pay_date']){
			$_POST['payment_loan'] = 0;
			$_POST['loan_application_date'] = "0000-00-00";
			array_push($contract_field2 , "payment_loan","loan_application_date");
		}

		// 契約待ちから支払方法変更,status:7->1,wait_flg=1(ローン取消後集計のため)
		if($_POST['payment'] && $contract['status']==7){
			$_POST['status']=0;
			$_POST['wait_flg']=1;
			array_push($contract_field2 , "status", "wait_flg");
		}

		// ローン不備チェック判定（チェック入りだった場合のみ 5.ローン不備=>6.承認中(OK)に変更する 2017/08/18 add by shimada
		if($_POST['if_loan_deficiency'] && $contract['loan_status']==5){
			$_POST['loan_status']=6;
			array_push($contract_field2,  "loan_status");
		}

		// update contract table
		 $_POST['contract_id'] = Update_Data("contract",$contract_field2,$_POST['contract_id']);
		 // 自動終了者に終了日を与えた後リセット　append by ka 20170223
		 unset($_POST['cancel_date']);

		// 予約tableに反映-------------------------------------------------------
		$_POST['reg_flg'] = 1;

		// 20151021 "part"を追加 shimada
		$reservation_field = array("contract_id","sales_id","course_id","reg_flg","rstaff_id","part","edit_date");

		// トリートメントカルテに回数として使っている
		if( $_POST['if_service'] ) array_push($reservation_field,  "r_times");

		if(isset($_POST['cstaff_id'])) array_push($reservation_field,  "cstaff_id");
		if(isset($_POST['tstaff_id'])) array_push($reservation_field,  "tstaff_id", "tstaff_sub1_id", "tstaff_sub2_id");

		// 更新
		$data_ID = Update_Data($table ,$reservation_field,$data['id']);

		// Msg-------------------------------------------------------------------
		if( $data_ID ) {
			// // 回数制か、返金保証回数終了後のコースで消化回数が上限に達したとき
			// if(  $contract['status'] == 0 && $_POST['course_type']==0 && $_POST['r_times'] >= $_POST['times'] && $_POST['if_service']==1  ){
			// 	// 返金保証回数終了後のコース切替フラグが立っていたら返金保証回数終了のステータスで終了
			// 	if( $course['zero_flg']==1 ){
			// 		$gMsg = '（返金保証回数終了しました）'; // 返金保証回数終了
			// 	}else if($course['zero_flg']==0){
			// 		$gMsg = '（契約終了しました）'; // 契約終了
			// 	}
			// } else {
				$gMsg = '（完了）';
			// }
			// $complete_flg = 1;

			// レジ一覧へ
			// if($_POST['payment'] || $_POST['option_price'] || $_POST['option_transfer'] || $_POST['option_card'])	header('location: ../account/?pay_date='.$_POST['pay_date'].'&mode=display');

			// 役務残一覧へ
			// elseif($_POST['if_service']) header('location: ../service/?latest_date='.$_POST['latest_date']);

		} elseif (!$data['id'] && $_POST['sales_id']) {
			// 予約なしのレジ精算の場合、リダイレクト用のフラグを立てる
			$complete_flg = true;
			$sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($_POST['sales_id'])."'");
		}else $gMsg = '（登録しませんでした。)';
	}
}

// 詳細を取得----------------------------------------------------------------------------
// 通常のレジ精算
if( $_POST['reservation_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
	if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
	else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' and (status=0 or status=4 or status=5 or status=7) and (end_date>='".date("Y-m-d")."' or end_date='0000-00-00') order by status in (0,7) desc, contract_date desc, id desc ");
	$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($data['course_id'])."'");
// }elseif($_POST['customer_id'] != ""){
// 	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
// 	// 売掛回収
// 	if(!$_POST['type']) $_POST['type'] = 7;
// 	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
// 	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

// 月額支払、その他精算(新規)
} elseif ($_POST['contract_id'] != "") {
	$contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($contract['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($contract['shop_id'])."'");
	$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($contract['course_id'])."'");

	// $dataに値を格納
	$data = array();
	$data['shop_id'] = $shop['id'];
	if ($_POST['type']) $data['type'] = $_POST['type'];
	$data['hope_date'] = date("Y-m-d");

// 月額支払、その他精算(編集)
} elseif ($_POST['sales_id'] != "") {
	$sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($_POST['sales_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($sales['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($sales['shop_id'])."'");
	if ($sales['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($sales['contract_id'])."'");
	$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($sales['course_id'])."'");

	// $dataに値を格納
	$data = array();
	// if ($sales['reservation_id']) $data['id'] = $sales['reservation_id'];
	$data['shop_id'] = $shop['id'];
	$data['type'] = $sales['type'];
	$data['hope_date'] = $sales['pay_date'];
	$data['rstaff_id'] = $sales['rstaff_id'];
}
// 未精算かつ区分が月額支払の場合、オプションで月額支払を初期選択
$kbn = $data["rsv_status"] ? $data["rsv_status"] : $data["type"]; // 区分
$option_name = isset($sales['option_name']) ? $sales['option_name'] : ($kbn == 8 ? 4 : 0);
$option_name_flg = isset($sales['option_name']) ? false : true;

// 割引率の計算 2016/12/16 add by shimada
if($customer['introducer_type']==3){
	// スタッフ紹介
	$rate_intro = 0.2;
} else if($customer['introducer_type']==5){
	// 企業紹介
	$rate_intro = 0.1;
} else {
	// 紹介なし
	$rate_intro = 0;
}

// 単価の種類を定義
$price_once    = 0;                                                 // 消化単価(初期化)
if($sales['times']){
	$per_price_dis = round(($sales['fixed_price']-$sales['discount'])/$sales['times']);                      // 割引単価
} else {
	$per_price_dis = 0;                      // 割引単価
}
//$per_price_dis = round(($sales['fixed_price']-$sales['discount'])/$sales['times']);                      // 割引単価
//$per_price     = round($sales['fixed_price']*(1-$rate_intro)/$sales['times']);// 通常単価
$per_price_adj = ($sales['fixed_price']-$sales['discount'])-($sales['times']-1)*$per_price_dis;          // 調整単価
if($sales['times']){
	$per_price     = round($sales['fixed_price']*(1-$rate_intro)/$sales['times']);// 通常単価
} else {
	$per_price     = 0;// 通常単価
}

// コース別 加算値を設定 2016/12/16 add by shimada
// 旧月額/パック ×1回毎、新月額 ×2回毎
$course_plus = 1;
if($course['new_flg']){
	// 加算値
	$course_plus = 2;
}

// 消化単価を計算する 2016/12/16 add by shimada
if($course['type']){
// 月額処理
	// 割引期間内(割引最終回を含む)
	if( ($data['r_times']-1)*$course_plus < $sales['times'] && $sales['r_times']*$course_plus >= $sales['times']){
		// コース回数で偶数・奇数のときの計算
		if($sales['times']%2==1){// 奇数
			// 全身:2回分、半身:1回分の振り分け
			if($data['part']==0){
				// 調整単価+通常単価
				$price_once = $per_price_adj+$per_price;
			} else {
				// 調整単価
				$price_once = $per_price_adj;
			}
		} else { // 偶数
			// 全身:2回分、半身:1回分の振り分け
			if($data['part']==0){
				// 調整単価+割引単価
				$price_once = $per_price_adj+$per_price_dis;
			} else {
				// 割引単価(運用上想定なし)
				$price_once = $per_price_dis;
			}
		}
	}
	// 割引期間内+割引期間外(割引最終回は含まない)
	elseif($sales['r_times']*$course_plus < $sales['times'] && $sales['r_times'] > 0){
		// 全身:2回分、半身:1回分の振り分け
		if($data['part']==0){
			// 割引単価*$course_plus
			$price_once = $per_price_dis *$course_plus;
		} else {
			// 割引単価
			$price_once = $per_price_dis;
		}
	}
	// 通常の消化(消化以外のレジ精算もここ)
	 else {
	 	// 全身:2回分、半身:1回分の振り分け
	 	if($data['part']==0){
	 		$price_once = $per_price*$course_plus;
	 	} else {
	 		$price_once = $per_price;
	 		//ホットペッパー月額ケース(既存)
	 		if($sales['course_id']==70){
	 		    $price_once = $course_price['45']*1.08/$course_times['45']; //消費税1.08に固定
	 		}
	 	}
	}
} else {
	$price_remain = 0;
	// 割引単価
	$price_once = $per_price_dis;
	// 役務残(請求金額-消化済単価)
	$price_remain = $sales['price'] - $price_used ;
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
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
$course_list[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type']; // コースタイプ 0.パック、1.月額 20161013 shimada追加
}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company","-","","rank");
$loan_company_id = $_POST['loan_company_id'] ? $_POST['loan_company_id'] : $contract['loan_company_id'] ;

// 全角から半角へ
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

$pdf_param = "?shop_name=".$shop['name'].
			  "&shop_zip=".$shop['zip'].
			  "&shop_pref=".$gPref[$shop['pref']].
			  "&shop_address1=".$shop_address1.
			  "&shop_address2=".$shop_address2.
			  "&shop_tel=".$shop['tel'].
			  "&name=".$customer['name'].
			  "&course_name=".$course_list[$contract['course_id']].
			  "&course_type=".$course['type'].
			  "&fixed_price=".$contract['price'].
			  "&discount=".$sales['discount'].
			  "&payment=".$sales['payment'].
			  "&option_name=".($sales['option_name'] ? $gOption[$sales['option_name']] : "オプション").
			  "&option_price=".($sales['option_price']+$sales['option_transfer']+$sales['option_card']).
			  "&balance=".$sales['balance'].
			  "&times=".$contract['times'].
			  "&r_times=".$contract['r_times'].
			  "&per_price=".$price_once;

$point = $sales['point'] ? $sales['point'] : $data['point'];

//tax
if ($data['hope_date'] < "2014-04-01") {
    $tax = 0.05;
    $tax2 = 1.05;
} else if ($data['hope_date'] < "2019-10-01") {
    $tax = 0.08;
    $tax2 = 1.08;
} else {
    $tax_data = Get_Table_Row("basic", " WHERE id = 1");
    $tax = $tax_data['value'];
    $tax2 = 1 + $tax_data['value'];
}
