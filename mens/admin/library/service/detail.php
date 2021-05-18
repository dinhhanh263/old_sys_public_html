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

$table = "reservation";


if( $_POST['id']) $reservation = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
if($reservation['sales_id']) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($reservation['sales_id'])."'");

//契約詳細取得-------------------------------------------------------------------
//contract_idが予約詳細から引き続く
if($reservation['multiple_contract_id']) {
	// 今回予約チェックをした複数契約データ
	$contract_array = Get_Table_Array("contract","*"," WHERE del_flg=0 and customer_id <>0 and id in (".addslashes($reservation['multiple_contract_id']).") and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00')"." and contract_date <= '".$reservation['hope_date']."'");
	// 前回予約チェックをした複数契約データ 2017/05/10 add by shimada
	$last_sales_array = Get_Table_Array("sales","*"," WHERE del_flg=0 and reservation_id = '".addslashes($reservation['id'])."'");

	// 当日ローン取消、支払方法変更での対応
	if($contract['new_contract_id'] && $contract['cancel_date'] == $reservation['hope_date']){
		$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id <>0  and id = '".addslashes($contract['id'])."'");
		$_POST['contract_id'] = $contract['id'];
	}
}

// 今回予約チェックした 親契約ID(pid)を算出する
$contract_array_count = count($contract_array);
// 消化回数超過フラグ 2017/06/09 add by shimada
$r_times_over_flg=false;// 超過していない
foreach ($contract_array as $key => $value) {
	// 親契約ID(pid)
	$pid_array[] = $value['pid'];
	// 消化回数を超えている（返金保証期間終了後の0回のコース除外） 2017/06/09 add by shimada
	if($value['times']<=$value['r_times'] && $value['times']<>0){
		// 消化回数超過フラグ
		$r_times_over_flg=true;// 超過している
}
}

// 親契約IDのユニークIDを取得
$pid = array_unique($pid_array);
asort($pid); // 親契約の古いID順にソート
$_POST['pid'] = implode(",", $pid);

// 前回予約チェックした 親契約ID(pid)を算出する 2017/05/10 add by shimada
$last_sales_array_count = count($last_sales_array);
foreach ($last_sales_array as $key => $value) {
	$last_pid_array[] = $value['pid'];
}
// 親契約IDのユニークIDを取得
$last_pid = array_unique($last_pid_array);
asort($last_pid); // 親契約の古いID順にソート

// 売掛回収時のみチェック
// 原則同じ契約日ごとでの売掛回収となる
if( $reservation['type']== 7 || $reservation['type']== 27) {
	// pidが複数ある場合、エラー表示
	if(count($pid) ==0){ // pidが一つもない場合
		$gMsg = '「予約する」にチェックがありません。前の画面でチェックをしてからレジ清算してください。';
	} else if( 1 < count($pid)){ // pidが1つ以上
		$gMsg = '同じ契約番号のコースで清算してください';
	} else {
		// 親契約tableの情報取得
		$contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and customer_id <>0 and id = ".$_POST['pid']." and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");
	}
}

// トリートメント時のみチェック
// 原則同じ契約日ごとでのsales_id発行となる
$chk_flg=false; // 予約チェックなし
$contract_p_array_flg =0;
if( $reservation['type']== 2 || $reservation['type']== 27) {
	// 前回予約チェックしたpidが、今回チェックされていない場合、論理削除する 2017/05/09 add by shimada ///////////
	$delete_sales_array = array();
	$delete_sales_array = array_diff($last_pid,$pid);

	// 削除対象の契約ID毎に契約データ、消化履歴データを更新する
	foreach ($delete_sales_array as $delete_pid) {
		// 削除フラグ(初期化)
		$_POST['del_flg']=0;
		// 削除対象の売上データを論理削除する
		$_POST['del_flg']=1;
		Update_Data_Where("sales",array("del_flg")," del_flg=0 AND pid='".addslashes($delete_pid)."' AND reservation_id ='".addslashes($reservation['id'])."'");
	}
	///////////////////////////////////////////////////////////////////////////////////////////

	// pidが複数ある場合、エラー表示
	if(count($pid) ==0){ // pidが一つもない場合
		$gMsg = '「予約する」にチェックがありません。前の画面でチェックをしてからレジ清算してください。';
		$chk_flg=true; // 予約チェックあり
	} else if( 1 < count($pid)){
		$contract_p_array_flg =1;
		if($_POST['pid'])$contract_p_array = Get_Table_Array("contract_P","*"," WHERE del_flg=0 and customer_id <>0 and id in (".$_POST['pid'].") and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");
	} else {
		// 親契約tableの情報取得
		if($_POST['pid'])$contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and customer_id <>0  and id = ".$_POST['pid']." and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");
	}
}

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {

	$editable_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-0, date("Y")));
	$editable_date2 = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer']  + $_POST['payment_loan']; //支払金額＝現金+カード+振込+ローン

	// submit制御----------------------------------------------------------------
	$option_message_time = '';    // 未来のエラーメッセージ
	$option_message_month = '';   // 月のエラーメッセージ
	$month_null_flg = ($_POST['option_month'] ===null || $_POST['option_month'] =='') ? true:false; // 月のnull判定（0で未入力判定されてしまうため）
	
	// 月のデータ整形（エラーチェックの前に正しい形にする）
	$_POST['option_month'] = is_numeric(Che_Num3($_POST['option_month'])) ? Che_Num3($_POST['option_month']):$_POST['option_month'] ; // 数値であれば月を半角/先頭0なしに整形する
	$_POST['option_month'] = Comma($_POST['option_month']);    // 半角カンマ以外の記号を半角カンマに整形する
	
	// 年と月の未来日チェックを行う
	if(is_numeric($_POST['option_month'])){
		$date_flg = checkTerm($_POST['option_year'], $_POST['option_month'],'',3,1,'m');
		if($date_flg ==='f'){
			$option_message_time = '1ヶ月以上未来に清算できません。';
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
			$month = Che_Num3($month); //半角に整形する
			$date_flg = checkTerm($_POST['option_year'], $month,'',3,1,'m');
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
				$option_message_time = '1ヶ月以上未来日に清算できません。';
			}		
		}
		// 月のエラーがない場合、チェック済みの配列をカンマ区切りの形式に戻す
	    $result = sort($months, SORT_NUMERIC); //　カンマ区切りの月をソート
		$_POST['option_month'] = implode(',', $months);
	}

	// submit制御----------------------------------------------------------------
	if(!$_POST['id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※予約自体が未登録です。</span>";

	}elseif($authority_level>6 && $reservation['hope_date']<$editable_date){
		$gMsg = "<span style='color:red;font-size:13px;'>※本日前のデータが編集不可です。</span>";

	}elseif($authority_level>=1 && $authority_level<=6 && $reservation['hope_date']<$editable_date2){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";

	// 消化回数超過チェック 2017/06/09 add by shimada
	}elseif($authority_level>=1 && $r_times_over_flg==true && $_POST['if_service1']){
		$gMsg = "<span style='color:red;font-size:13px;'>※消化回数を超過しているコースがあるため役務消化できません。</span>";

	}elseif($authority_level>=1 && ($_POST['type']==7 || $_POST['type']==10 ) && $_POST['if_service']){
		$gMsg = "<span style='color:red;font-size:13px;'>※売掛回収やプラン変更で役務消化できません。</span>";

	}elseif($authority_level>6 && $_POST['payment'] && $_POST['price']<($_POST['payment'])){
		$gMsg = "<span style='color:red;font-size:13px;'>※支払金額が売掛金より大きいです。</span>";

	}elseif($authority_level>=1 && $reservation['hope_date']>date('Y-m-d') && ($_POST['payment']<>0 || ($_POST['option_price'] + $_POST['option_transfer'] + $_POST['option_card'])<>0)){
		$gMsg = "<span style='color:red;font-size:13px;'>※未来日にレジ精算できません。</span>";

	}elseif($_POST['option_name']==4 && $_POST['option_year']=='-'){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※何年分を選択してください。</span>";

	}elseif($_POST['option_name']==4 && $month_null_flg===true){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※何月分を入力してください。</span>".$_POST['option_month'];

	}elseif($_POST['option_name']==4 && $option_message_month<>''){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※".$option_message_month."</span>";

	}elseif($_POST['option_name']==4 && $option_message_time<>''){
		$gMsg = "<span id='g_error' style='color:red;font-size:13px;'>※".$option_message_time."</span>";

	//ホットペッパー月額120分の初回を除外。course_id=70
	// }elseif($_POST['course_type'] && !($_POST['course_id']==70 && ($_POST['r_times']==0 || $sales['r_times']==1) ) && ($_POST['if_service'] || $sales['r_times']) && !$_POST['part']){
	// 	$gMsg = "<span style='color:red;font-size:13px;'>※施術部位を選択してください。</span>";
		
	}elseif($authority_level>6 && !$_POST['rstaff_id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※レジ担当を選択してください。</span>";
	}else{
		//データ取得----------------------------------------------------------------
		// if( $_POST['course_id'] != "" )  	 $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");
		
		// //契約詳細取得-------------------------------------------------------------------
		// //contract_idが予約詳細から引き続く
		// if($reservation['multiple_contract_id']) {
		// 	$contract_array = Get_Table_Array("contract","*"," WHERE del_flg=0 and customer_id <>0 and id IN (".addslashes($reservation['multiple_contract_id']).")");

		// 	// 当日ローン取消、支払方法変更での対応
		// 	if($contract['new_contract_id'] && $contract['cancel_date'] == $reservation['hope_date']){
		// 		$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id <>0  and id = '".addslashes($contract['id'])."'");
		// 		$_POST['contract_id'] = $contract['id'];
		// 	}
		// }

		// 複数契約IDからコースIDを取得する
		$multiple_contract_id_array = explode(",", $reservation['multiple_contract_id']);
		foreach ($multiple_contract_id_array  as $key => $value) {
			//　契約IDから契約コースを割り出す
			$contract_result = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($value)."'");
			$contract_id_array[]         = $contract_result['course_id'];    // 複数コースID
		}
		// 複数契約コースIDをカンマ区切りで格納する
		$_POST['multiple_course_id'] = implode(",", $contract_id_array);	// 予約した複数コースID
		$multiple_course_id          = $_POST['multiple_course_id'];	 // 予約した複数コースID


		//売上tableに反映----------------------------------------------------------
		//コース変更、途中解約を考慮し、現情報をテーブルに

		$_POST['reservation_id'] = $_POST['id'];
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		//$_POST['pay_date'] = $reservation['hope_date'];
		$_POST['staff_id'] = $_POST['cstaff_id'] ? $_POST['cstaff_id'] : $_POST['tstaff_id'];
		// $_POST['price'] = isset($sales) ? $sales['price'] : $contract_p['balance'] ; //前回までの請求金額（残り）
		$_POST['balance'] = $_POST['price'] - $_POST['payment']; //売掛金
		// 消化
		if($sales['r_times_flg'] ==1 || $_POST['if_service1']==1){
			$_POST['r_times_flg'] = 1;
		}

		//新規のみ、基本情報登録
		$sales_field  = array("pid","contract_id","r_times_flg","type","reservation_id","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","times","fixed_price","discount","option_name","option_price","option_transfer","option_card","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","reg_date","edit_date");
		$sales_field2 = 			        array("r_times_flg","type",								  "shop_id","staff_id","rstaff_id","multiple_course_id",		            "times",						 "option_name","option_price","option_transfer","option_card","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date",           "edit_date");
		// if($sales['r_times_flg'] ==1 || $_POST['if_service1']==1) {
		// 	array_push($sales_field , "r_times_flg");
		// 	array_push($sales_field2 , "r_times_flg");
		// }
		if($_POST['option_name'] == 4) {
			array_push($sales_field , "option_year");
			array_push($sales_field2 , "option_year");
			array_push($sales_field , "option_month");
			array_push($sales_field2 , "option_month");
			array_push($sales_field , "option_date");
			array_push($sales_field2 , "option_date");
		}

		// 更新 or 新規
		// トリートメント清算時 pidが複数ある場合sales_idを複数発行する
		if($contract_p_array_flg === 1){
			// 親契約IDごとにsales_idを発行し、登録/更新していく
			foreach ($contract_p_array as $key => $value) {
				// オプション金額は初回のみ売上につける
				if ($key === 0) {
					$_POST['option_name']			= $_POST['option_name'];
					$_POST['option_price']			= $_POST['option_price'];
					$_POST['option_cash']			= $_POST['option_cash'];
					$_POST['option_transfer']		= $_POST['option_transfer'];
					$_POST['option_card']			= $_POST['option_card'];
				} else {
					$_POST['option_name']			= 0;
					$_POST['option_price']			= 0;
					$_POST['option_cash']			= 0;
					$_POST['option_transfer']		= 0;
					$_POST['option_card']			= 0;
				}			
				// pidごとの項目セット
				$_POST['pid']					= $value['id'];
				$_POST['reservation_id']		= $_POST['reservation_id'];
				$_POST['multiple_course_id']	= $value['multiple_course_id'];
				$_POST['fixed_price']			= $value['fixed_price'];
				$_POST['discount']				= $value['discount'];
				$_POST['balance']				= $value['balance'];
				// トリートメントの時は売掛回収はしないため、0円を入れる 20160826 add by shimada
				$_POST['price']					= 0;
				$_POST['payment']				= 0;
				$_POST['pay_type']				= 0;
				$_POST['payment_cash']			= 0;
				$_POST['payment_card']			= 0;
				$_POST['payment_transfer']		= 0;
				$_POST['payment_loan']			= 0;
				
				// 既存処理　下記に書き換えています。 ///////////////////////////////////////////////////////////////////////////////////////////
				// レジ清算フラグ reservation.reg_flg=1であれば更新する
				// reservation_idとpidが一致するsales_idをすべて更新する
				// if($reservation['reg_flg'] ==1) $_POST['sales_id'] = Update_Data_Where("sales",$sales_field2,"del_flg=0 and pid = ".addslashes($value['id'])." and reservation_id =".addslashes($reservation['id'])); //再度精算、前回精算の取り消し,編集の場合、消化回数が計上しない
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// 売上データに同じpidレコードがあった場合は更新、なかった場合は売上レコードを登録する 2017/05/09 add by shimada
				$sales_update_flg=false; // 売上データ更新フラグ
				if(Get_Table_Col("sales","id"," WHERE del_flg=0 and pid = ".addslashes($value['id'])." and reservation_id =".addslashes($reservation['id']))){
					$sales_update_flg=true;
				}
				// 売上データ更新フラグあり(更新)
				if($sales_update_flg==true){
					$_POST['sales_id'] = Update_Data_Where("sales",$sales_field2,"del_flg=0 and pid = ".addslashes($value['id'])." and reservation_id =".addslashes($reservation['id'])); //再度精算、前回精算の取り消し,編集の場合、消化回数が計上しない
				// 売上データ更新フラグなし(登録)
		} else {
					$_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）
				}
			}
			// それ以外はすべてsales_idを1レコード発行する
		} else {
			// 　以前の記述 //
			// if($reservation['sales_id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$reservation['sales_id']); //再度精算、前回精算の取り消し,編集の場合、消化回数が計上しない
			// else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）
			// 前回チェックした親契約IDがなく、今回チャックした親契約IDがあった場合は売上レコードを再発行する 2017/05/31 add by shimada
			if(is_null($last_pid) && $pid<>"") $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）
			else $_POST['sales_id'] = Update_Data("sales",$sales_field2,$reservation['sales_id']); //再度精算、前回精算の取り消し,編集の場合、消化回数が計上しない
		}

		// 予約詳細ページで予約チェックした契約
		$select_contract      = Get_Table_Array("contract","*", " WHERE del_flg=0 and customer_id ='".addslashes($reservation['customer_id'])."' and id in (".$reservation['multiple_contract_id'].")"); //複数契約情報 契約中のデータのみ取得
		
		// 今回予約チェックしなかったコースの消化を契約データから減算し、消化履歴データを削除する処理　2017/05/09 add by shimada /////////////////////////////////////////
		// 今回予約チェックした契約と消化履歴で消化済みの契約を比較する
		$diff_contract        = array();
		$diff_r_times_history = array();
		$diff_contract        = Get_Table_Array_Multi("contract"," id,course_id,r_times ", " WHERE del_flg=0 and customer_id ='".addslashes($reservation['customer_id'])."' and id in (".$reservation['multiple_contract_id'].") ORDER BY id ASC"); //複数契約情報 契約中のデータのみ取得
		$diff_r_times_history = Get_Result_Sql_Array(" SELECT contract_id FROM r_times_history WHERE del_flg=0 and customer_id ='".addslashes($reservation['customer_id'])."' and reservation_id ='".addslashes($reservation['id'])."' GROUP BY contract_id ORDER BY contract_id ASC"); //複数契約情報 消化済みのデータ取得

		// 消化履歴で消化済みのコースIDの配列(前回消化済み)
		foreach ($diff_r_times_history as $diff_r_times_history_value) {
			$contract_id_array1[] = $diff_r_times_history_value['contract_id'];
		}
		// 今回予約チェックしたコースIDの配列(今回消化予定)
		foreach ($diff_contract as $diff_contract_value) {
			$contract_id_array2[] = $diff_contract_value['id'];
		}
		// 前回消化済みコースが今回消化予定コースになかったら、消化回数を減算し、消化履歴を論理削除する
		$delete_contract_id_array ="";
		$delete_contract_id_array = array_diff($contract_id_array1,$contract_id_array2); // 削除対象の契約ID
		// 削除対象の契約ID毎に契約データ、消化履歴データを更新する
		foreach ($delete_contract_id_array as $delete_contract_id) {
			//　消化履歴データを論理削除する
			$_POST['del_flg']=1;
			// 消化履歴table更新
			Update_Data_Where("r_times_history",array("del_flg")," del_flg=0 AND contract_id= '".addslashes($delete_contract_id)."' AND reservation_id ='".addslashes($reservation['id'])."'");

			// 契約データの消化回数を減算し、更新する
			$delete_contract  =""; // 削除対象の契約データ(初期化)
			$_POST['r_times'] = 0; // 消化回数(初期化)
			// 削除対象の契約データ
			$delete_contract             = Get_Table_Row("contract"," WHERE id='".addslashes($delete_contract_id)."'");
			// ひとつ前の最終消化日がある場合、消化管理データから取得する(ない場合は"0000-00-00で上書きする")
			$delete_contract_latest_date = Get_Table_Col("r_times_history","pay_date"," WHERE del_flg=0 AND contract_id='".addslashes($delete_contract_id)."' ORDER BY r_times DESC LIMIT 0,1");
			if(!$delete_contract_latest_date)$delete_contract_latest_date ="0000-00-00";
			$_POST['r_times']     = $delete_contract['r_times']-1; // 消化回数を-1する
			$_POST['latest_date'] = $delete_contract_latest_date;  // ひとつ前の最終消化日
			// 契約table更新
			Update_Data_Where("contract",array("r_times","latest_date")," del_flg=0 AND id='".addslashes($delete_contract_id)."' ");

		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//消化回数、売掛金、最新来店日を契約tableに反映-------------------------------
		$_POST['latest_date'] = $reservation['hope_date'];

		// 現在契約中
		$all_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and customer_id ='".addslashes($reservation['customer_id'])."' and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'"); 
		// $contract_field2 = array("balance","edit_date");
		$contract_p_field2 = array("balance","edit_date");

		// 契約テーブルに今回施術した契約IDの消化回数を追加する 20160125 shimada
		$r_times_history2 = array(                                                              "shop_id","staff_id","rstaff_id","type",                                                                                                                       "pay_date","memo",           "edit_date");

		// 消化処理後に使うデータをいったん変数に格納する
		$before_contract_value  = array();
		$before_r_times_history = array();
			foreach ($select_contract as $key => $value) {
				// トリートメント時のみ消化する
				$select_pid_array[] = $value['pid'];
				// トリートメント時のみ消化する
				if($_POST['type'] == 2 || $_POST['type'] == 27) {
					$_POST['r_times'] = 0; //複数回すため最初に初期化
					// 既存の消化回数に今回の消化を加える
				$before_contract_value = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($value['id'])."'");
				if( $before_contract_value['course_id'] != "" ) $before_course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($before_contract_value['course_id'])."'");
				
				// 消化済みのコースを取得する 2017/05/09 add by shimada
				// $before_r_times_history = Get_Table_Row("r_times_history"," WHERE del_flg=0 AND course_id = '".addslashes($before_contract_value['course_id'])."' and reservation_id= '".addslashes($reservation['id'])."'");
				$before_r_times_history = Get_Table_Row("r_times_history"," WHERE del_flg=0 AND contract_id = '".addslashes($before_contract_value['id'])."' and reservation_id= '".addslashes($reservation['id'])."'");
				
				$_POST['status']       = $before_contract_value['status'];      // 現在の契約ステータス
				$_POST['cancel_date']  = $before_contract_value['cancel_date']; // 現在の解約日
				
				// 消化済みのコースが取得できなかったら、消化回数を加算する　2017/05/09 add by shimada ////////////////////////////////////
				if(!$before_r_times_history){
					// $sales_idセット用 2017/08/10 add by shimada
					$sales_id_flg=false;

					// 消化回数+1 する
					$_POST['r_times']      = $before_contract_value['r_times'] +1;
				////////////////////////////////////////////////////////////////////////////////////////////////////////////

					// 消化回数がコース回数上限に達したら、「契約終了」にする
					$contract_field2 = array("r_times","edit_date");
					// 消化回数がコース回数上限に達したら、「契約終了」にする
					if($_POST['r_times'] == $before_contract_value['times']){
						if($before_course['zero_flg']==1) $_POST['status'] = 8; // 返金保証回数終了
						else $_POST['status'] = 1;                              // 契約終了
						$_POST['cancel_date'] = $reservation['hope_date']; // 契約終了日
						array_push($contract_field2 , "status","cancel_date"); // 最終消化日、施術のみ
					}
					array_push($contract_field2 , "latest_date","r_times");     // 最終消化日、施術のみ r_times追加 2017/05/09 add by shimada
					Update_Data("contract",$contract_field2,$value['id']);  	// 契約tableの更新 

					$after_contract_value = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($value['id'])."'"); // 消化処理後
					$_POST['r_times']  = $after_contract_value['r_times']; // 消化情報を引き継ぐ

					// 返金保証回数終了したコースを0円コースに切り替える 20160602 shimada----------------------------------------------------------------------
					if($_POST['status']==8 || $value['status']==8){
						// 返金保証回数終了コースへ切り替えたpidの配列作成(親契約テーブル更新用)
						$pid_include_zero_array[] = $value['pid']; 

						// 新契約tableに反映----------------------------------------------------------
						// 編集場合の対応？新契約がまた新規？
						$_POST['old_contract_id'] = $value['id'];
						$_POST['old_course_id']   = $value['course_id'];
						
						if(!$_POST['staff_id'])$_POST['staff_id'] = $value['staff_id'];
						$_POST['pid']             = $value['pid'];           // 親契約IDを引き継ぐ
						$_POST['part_time_sum']   = $value['part_time_sum']; // 所要時間を引き継ぐ

						// 0円コース courseデータ取得----------------------------------------------------------------
						$new_course = Get_Table_Row("course"," WHERE del_flg=0 and 1000 < id and group_id='".$before_course['group_id']."'"); // 保証期間終了(0円コース)に切替え
						$new_course_id_array = array($_POST['pid'],$new_course['id']); // 親契約テーブル更新用
						// 親契約IDの売上データ
						$sales_value = Get_Table_Row("sales"," WHERE del_flg=0 and pid = ".addslashes($before_contract_value['pid'])." and reservation_id =".addslashes($reservation['id'])); 
						$_POST['sales_id']         = $sales_value['id'];             	      // 売上ID
						$_POST['course_id']        = $new_course['id'];
						$_POST['times']            = $new_course['times'];
						$_POST['fixed_price']      = $new_course['fixed_price'];
						$_POST['discount']         = 0;
						$_POST['price']            = 0;
						$_POST['payment']          = 0;
						$_POST['payment_cash']     = 0;
						$_POST['payment_card']     = 0;
						$_POST['payment_transfer'] = 0;
						$_POST['payment_loan']     = 0;
						$_POST['balance']          = 0;
						$_POST['memo']             = $reservation['hope_date'].":返金保証回数終了";
						$_POST['contract_date']    = $reservation['hope_date']; // 返金保証回数終了日

						// sales_id,reservation_idを新しい契約コースレコードに追加
						$new_contract_field  = array("sales_id","reservation_id","pid","old_contract_id","old_course_id","customer_id","shop_id","staff_id","course_id","times","part_time_sum","r_times","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","contract_date","reg_date","edit_date");// 返金保証回数終了しているためend_date更新なし。
						$new_contract_field2 = array("sales_id","reservation_id","pid","old_contract_id","old_course_id","customer_id",		   "staff_id","course_id","times","part_time_sum","r_times","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","contract_date","edit_date");
						
						// 更新 or 新規
						if($value['new_contract_id']) $_POST['new_contract_id'] = Update_Data("contract",$new_contract_field2,$value['new_contract_id']);
						else $_POST['new_contract_id'] = Input_New_Data("contract",$new_contract_field);//新規

						// 旧契約tableに反映-------------------------------------------------------------
						$_POST['new_course_id'] = $_POST['course_id'];
						if(!$_POST['if_cancel_date']) $_POST['if_cancel_date'] =0;
						$contract_field2 = array("new_contract_id","new_course_id","status","sales_id","cancel_date","if_cancel_date","edit_date","memo");
						// 更新
						if($value['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$value['id']);
						
					} // 返金保証回数終了したコースを0円コースに切り替える処理ここまで----------------------------------------------------------------------------------
					// 通常の契約終了
					else if($_POST['status']==1 || $value['status']==1){
						// 契約終了コースへ切り替えたpidの配列作成(親契約テーブル更新用)
						$pid_include_fin_array[] = $value['pid']; 
						// 売上IDを取得する(売上IDを都度発行する必要があるため追加) 2017/08/10 add by shimada
						$sales_id_flg=true;
					
					// 通常の消化時(売上IDを都度発行する) 2017/07/28 add by shimada
					} else {
						$sales_id_flg=true;
					}

					// 売上IDを取得する
					if($sales_id_flg==true){
						$sales_id = 0;
						$sales_id = Get_Table_Col("sales","id"," WHERE del_flg=0 and pid = ".addslashes($after_contract_value['pid'])." and reservation_id =".addslashes($reservation['id'])); 
						$_POST['sales_id']      = $sales_id;// 売上ID
					}

					// 消化履歴tableへ登録するためのデータをセットする
					$_POST['pid']           = $after_contract_value['pid'];           // 親契約ID
					$_POST['contract_id']   = $after_contract_value['id'];            // 契約ID
					$_POST['course_id']     = $after_contract_value['course_id'];     // コースID
					$_POST['contract_part'] = $after_contract_value['contract_part']; // 施術部位
					$_POST['part_time_sum'] = $after_contract_value['part_time_sum']; // 施術時間合計
					$_POST['times']         = $after_contract_value['times'];         // コース回数
					$_POST['fixed_price']   = $after_contract_value['fixed_price'];   // コース金額
					$_POST['discount']      = $after_contract_value['discount'];      // 割引
					$_POST['dis_type']      = $after_contract_value['dis_type'];      // 割引タイプ
					$_POST['price']         = $after_contract_value['price'];         // 請求金額
					$_POST['unit_price']    = $after_contract_value['unit_price'];    // 消化単価
					$_POST['status']        = $_POST['status'];                       // 契約ステータス
					$_POST['cancel_date']   = $_POST['cancel_date'];                  // 解約日

					// 消化table登録
					$r_times_history  = array("sales_id","pid","contract_id","status","cancel_date","reservation_id","customer_id","shop_id","staff_id","rstaff_id","type","course_id","times","r_times","contract_part","part_time_sum","fixed_price","discount","dis_type","price","unit_price","pay_date","memo","reg_date","edit_date","r_times_flg");
					Input_New_Data("r_times_history",$r_times_history);       // 消化履歴tableへ登録
				} else {
					// 消化table更新
					$after_contract_value = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($value['id'])."'"); // 消化処理後
					Update_Data_Where("r_times_history",$r_times_history2,"del_flg=0 and contract_id = ".addslashes($after_contract_value['id'])." and reservation_id =".addslashes($reservation['id'])); 
				}				
				}
			}

			// 親契約IDのユニークIDを取得
		if($_POST['if_service1'] ==1){
			// 親契約IDのユニークIDを取得
			$select_pid_array = array_unique($select_pid_array);
			asort($select_pid_array); // 親契約の古いID順にソート
			$contract_p_status_field2 = array("status","edit_date"); // 親契約ステータス更新用
			foreach ($select_pid_array as $key => $pid_value) {

				// 返金保証回数終了したコース OR 通常の契約終了したコース の親契約IDが含まれていた場合、親契約テーブルの契約情報を再度算出する------------------------------------------------------------------------------
				if( in_array($pid_value, $pid_include_zero_array) || in_array($pid_value, $pid_include_fin_array) ){	

					// 親契約tableに登録するためのデータを新しく作成する
					$data_array = Get_Table_Array("contract","*"," WHERE status=0 and del_flg=0 and pid = '".addslashes($pid_value)."'");
					$data_p     = Get_Table_Row("contract_P"," WHERE del_flg=0 and id = '".addslashes($pid_value)."'");
					$fixed_price_sum = 0; // コース金額の合計(複数)
					$new_price       = 0; // 請求金額合計(初期化)
					foreach ($data_array as $key => $value) {
						$multiple_course_array[] = $value['course_id'];          // コースID
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
					$_POST['fixed_price']         = $new_fixed_price;       // コース金額合計
					$_POST['discount']            = $new_discount;			// 割引金額合計
					$_POST['price']               = $new_price;		    	// (割引後)請求金額合計
					$_POST['payment']             = $new_payment;			// 入金金額合計
					$_POST['payment_cash']        = $new_payment_cash;		// 入金金額(現金)
					$_POST['payment_card']        = $new_payment_card;		// 入金金額(カード)
					$_POST['payment_transfer']    = $new_payment_transfer;	// 入金金額(振込)
					$_POST['payment_loan']        = $new_payment_loan;		// 入金金額(ローン)
					$_POST['balance']             = $new_balance;		    // 売掛金
					// $_POST['balance']             = $data_p['balance'] + $_POST['balance'];                           // 売掛金残っていたら合算する

					//更新
					$contract_p_end_field2 = array("multiple_course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","edit_date","pay_complete_date");
					Update_Data("contract_P",$contract_p_end_field2,$pid_value); 
				}// 返金保証回数終了したコースの場合、上記処理に加えて新も反映させる--ここまで----------------------------------------------------------------------------

				// 親契約の契約ステータスを決める
				$status_data_array = Get_Table_Array("contract","*"," WHERE del_flg=0 and pid = '".addslashes($pid_value)."'");
				$_POST['status']   = Update_Contract_P_Status($status_data_array,$reservation['customer_id']);	
				//親契約の契約ステータスを更新する
				Update_Data("contract_P",$contract_p_status_field2,$pid_value);
			}

		}

		// 消化処理前のデータを改めてセットする
		//$contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and customer_id <>0 and id = ".$_POST['pid']." and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");

		//if($_POST['if_service'] && !$reservation['sales_id']) array_push($contract_field2 , "r_times"); // 初回のみ、消化回数計上（同じ日重複計上しない,前提は古いデータは編集禁止）
		// if($_POST['if_service'] ) array_push($contract_field2 , "r_times");
		// if($_POST['type'] == 2) array_push($contract_field2 , "latest_date"); // 最終消化日、施術のみ


		//注意：売上回収時最新来店日ではなく、予約日
		//if($_POST['payment'] && !$_POST['balance']) $_POST['pay_complete_date']= $reservation['hope_date'];
		/*	ローン承認中の掛回収、支払完了日に記入しない
			ローン承認済み後の掛回収、支払完了日を記入
			掛回収にローンがある、支払完了日に記入しない
		*/
		if( $_POST['payment']>0 && 
			( !($contract_p['payment_loan'] && $contract_p['loan_status']<>1) || $contract_p['loan_status']==1 ) && !$_POST['payment_loan'] && $_POST['balance']<=0 ){
			$_POST['pay_complete_date']= $reservation['hope_date'];//
			array_push($contract_p_field2 , "pay_complete_date"); 
 		}
 		//else $_POST['pay_complete_date'] = "0000-00-00";

 		// ローン支払合計と残掛け金　- ローンの掛け金（修正した金額）が0円超えたら、支払完了日を削除する 20151001 shimada
 		if($contract_p['balance'] > 0){
 			// ローン支払合計と残掛け金　- ローンの掛け金（修正した金額）が0円超えたら、支払完了日を削除する 20151001 shimada
			if(($contract_p['payment_loan'] + $contract_p['balance'] - $_POST['payment_loan'] > 0) && $_POST['balance']<>0){
				array_push($contract_p_field2 , "pay_complete_date"); 
				$_POST['pay_complete_date'] = "0000-00-00";
			}
 		} elseif(1 < $contract_p['loan_status'] && $_POST['balance']<>0) {
 				// ローンステータスが「取消」以外で売掛金が0円ではない場合、支払完了日を削除する 20160615 shimada
 				array_push($contract_p_field2 , "pay_complete_date"); 
				$_POST['pay_complete_date'] = "0000-00-00";
 		}

		//掛回収、二回目後のsubmit制御のため、初回のみ
		if(!$reservation['sales_id'] && $contract_p['balance']>0){ // 初回の売掛更新
			$_POST['payment_cash'] 		= $contract_p['payment_cash'] 	  + $_POST['payment_cash'];
			$_POST['payment_transfer'] 	= $contract_p['payment_transfer'] + $_POST['payment_transfer'];
			$_POST['payment_card'] 		= $contract_p['payment_card'] 	  + $_POST['payment_card'];
			array_push($contract_p_field2 , "payment_cash", "payment_transfer", "payment_card"); 
		}
		// 売掛金は更新されるが、各支払情報は二度目以降更新されない仕様。KIREIMOと同じ仕様ですが、解決策が見つからないため
		// 下記いったんコメントアウトさせていただきます。 20160615 shimada
		// elseif($reservation['sales_id']){ //2回目以降の更新
		// 	$_POST['payment_cash'] 		= $contract_p['payment_cash'] 	  - $sales['payment_cash']     + $_POST['payment_cash'];
		// 	$_POST['payment_transfer'] 	= $contract_p['payment_transfer'] - $sales['payment_transfer'] + $_POST['payment_transfer'];
		// 	$_POST['payment_card'] 		= $contract_p['payment_card'] 	  - $sales['payment_card']     + $_POST['payment_card'];
		// 	array_push($contract_p_field2 , "payment_cash", "payment_transfer", "payment_card"); 
		// }

		if($_POST['payment_loan'] ) array_push($contract_p_field2 , "payment_loan"); // ローン一覧に表示できるため、取り敢えず、ローンの金額を入れる。（ローンフラッグを追加した方がいい？なら、ローン金額が取得できない）

		//契約待ちから支払方法変更,status:7->1,wait_flg=1(ローン取消後集計のため)
		if($_POST['payment'] && $contract_p['status']==7){
			$_POST['status']=0;
			$_POST['wait_flg']=1;
			array_push($contract_p_field2 , "status", "wait_flg"); 
		} 

		//update contract table
		// $_POST['contract_id'] = Update_Data("contract",$contract_field2,$_POST['contract_id']);
		// 売掛回収時のみ更新する
		if($reservation['type'] ==7 || $reservation['type'] ==27){
			Update_Data("contract_P",$contract_p_field2,$_POST['pid']);

			// ローン非承認 OR ローン取消⇒契約復帰する場合、親契約データに合わせて契約テーブルを更新する 20160610 shimada
			// 売掛金を再び払ったら、ローンステータスがローン取消/非承認⇒承認中(デフォルト)に戻り、契約ステータスも契約待ち⇒契約中に戻る
			if( ($contract_p['balance'] <=0 || $_POST['balance'] <=0) && ($contract_p['loan_status']==2 || $contract_p['loan_status']==4) ){
				// 契約tableの契約待ち情報を改めて取得
				$contract_wait_array = Get_Table_Array("contract","*"," WHERE del_flg=0 and status=7 and customer_id <>0 and pid = ".$_POST['pid']." and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");

				$loan_contract_field2 = array("status","loan_status","payment_loan","cancel_date");
				foreach ($contract_wait_array as $key => $value) {
					$contract_value = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($value['id'])."'");
					if( $contract_value['course_id'] != "" ) $contract_value_course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($before_contract_value['course_id'])."'");
					// 売掛金0円で、ローン非承認 OR ローン取消の場合、契約中ステータスに戻す 20160610 shimada
					if( ($contract_p['balance'] <=0 || $_POST['balance'] <=0) && ($contract_p['loan_status']==2 || $contract_p['loan_status']==4) ){
						$_POST['status']             = 0;             // 0.契約中
						$_POST['loan_status']        = 3;             // ローンステータス 3.承認中
						$_POST['cancel_date']        = "0000-00-00";  // 解約日(初期化する)
						// 更新
						Update_Data("contract",$loan_contract_field2,$value['id']);
					}
				}
				// 親契約テーブルにも契約テーブルと同じ情報を登録する
				$_POST['status']             = 0;             // 0.契約中
				$_POST['loan_status']        = 3;             // ローンステータス 3.承認中
				$_POST['cancel_date']        = "0000-00-00";  // 解約日(初期化する)
				//$_POST['payment_loan_kari']  = $contract_p['payment_loan'];    // payment_loan_kariにローンしていた金額を履歴として残しておく
				//array_push($loan_contract_field2 , "payment_loan_kari");
				Update_Data("contract_P",$loan_contract_field2,$_POST['pid']);
			}
		
		}

		//予約tableに反映-------------------------------------------------------
		$_POST['reg_flg'] = 1;

		// レジ清算を更新時、reservation.sales_idは更新しない　update by 2017/02/28 shimada
		// 予約表「来店のまま」、予約詳細「役務消化（済）いずれもreservation.sales_idで判定しているため暫定対策です。
		// ※複数の親契約ID(contract_P.id)の消化を同時にするとき、初回はreservation.sales_idが片方のsalesレコードが入る仕様でしたが
		//  レジ清算更新時だとreservation.sales_id=0で更新されてしまうため分岐させました。
		if(empty($sales)){
		$reservation_field = array("contract_id","sales_id","course_id","reg_flg","rstaff_id","part","edit_date"); //20151021 "part"を追加 shimada
		} else {
			$reservation_field = array("contract_id","course_id","reg_flg","rstaff_id","part","edit_date"); //20151021 "part"を追加 shimada
		}
		
		//if($reservation['reg_flg']<>1 && $_POST['if_service']) array_push($reservation_field,  "r_times"); //使っていない
		//if( $_POST['if_service'] ) array_push($reservation_field,  "r_times"); //トリートメントカルテに回数として使っていたが、メンズは使わないためコメントアウト
			

		//月額：上半身90分--1、下半身60分--2、前提：必ず初回が下半身から、part_flgで調整。その後の来店なし消化は？
			// 上半身と下半身を繰り返す
		// if( $_POST['if_service'] || $sales['r_times']) {
		// 	// 上半身と下半身を繰り返す
		// 	if($_POST['r_times'] && $course['type'] && !$_POST['part']){
		// 		if($_POST['r_times'] % 2==1) $_POST['part'] = 2;
		// 		elseif($_POST['course_id']==70 && $_POST['r_times'] ==0) $_POST['part'] = 0; //初回ホットペッパーの方は全身(0)を指定する 20151019 shimada
		// 		else $_POST['part'] = 1;
		// 	}
		// 	array_push($reservation_field,  "part");
		// }

		if(isset($_POST['cstaff_id'])) array_push($reservation_field,  "cstaff_id");
		if(isset($_POST['tstaff_id'])) array_push($reservation_field,  "tstaff_id", "tstaff_sub1_id", "tstaff_sub2_id");
		
		// 削除済みデータを更新した場合　20151009 shimada
		//更新（sales.del_flg=1)なら、reservation.sales_idを0で上書きする
		// $sales_del_field = Get_Table_Row("sales"," WHERE del_flg=1 AND reservation_id = '".addslashes($_POST['id'])."'");
		// if($sales_del_field <>false){
		// 	$now = "'".date('Y-m-d H:i:s')."'";
		// 	$sql = "update reservation set sales_id=0,edit_date=".$now." where id=".$_POST['id'];
		// 	$sales_data_ID = $GLOBALS['mysqldb']->query($sql);
		// } else {
		//更新
		// 複数の親契約の契約IDを選んでいない場合のみ、予約tableへ親契約IDを反映する
		if($contract_p_array_flg==0) array_push($reservation_field,  "pid");
		$data_ID = Update_Data($table ,$reservation_field,$_POST['id']);
		//}

		//Msg-------------------------------------------------------------------
		if( $data_ID ) {
			$gMsg = '（完了）';
			$complete_flg = 1;
			//レジ一覧へ
			if($_POST['payment'] || $_POST['option_price'] || $_POST['option_transfer'] || $_POST['option_card'])	header('location: ../account/?pay_date='.$_POST['pay_date']);
			//役務残一覧へ
			elseif($_POST['if_service']) header('location: ../service/?latest_date='.$_POST['latest_date']);
		}else $gMsg = '（登録しませんでした。)';
		
		// 削除済みデータを更新した場合（メッセージ） 20151009 shimada
		// if($sales_data_ID){
		// 	$gMsg = '（清算データは削除済みです。)';
		// }
	}
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	//$contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}elseif($_POST['customer_id'] != ""){
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
	if(!$_POST['type']) $_POST['type'] = 7;//売掛回収
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}

//契約詳細取得-----------------------------------------------------------------------------
// 予約詳細ページで登録された契約IDのデータのみ取得(表示用)
if($reservation['multiple_contract_id'])$select_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and customer_id ='".addslashes($data['customer_id'])."'"." and id in (".$reservation['multiple_contract_id'].")"); //複数契約情報 契約中のデータのみ取得
if($_POST['pid']) $contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and customer_id <>0  and id in (".$_POST['pid'].")");

if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
//else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' and (status=0 or status=4 or status=5) and end_date>='".date("Y-m-d")."' order by reg_date desc ");
else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' and (status=0 or status=4 or status=5 or status=7) and (end_date>='".date("Y-m-d")."' or end_date='0000-00-00') order by reg_date desc ");

// 契約数からコースの数を取得し、配列に入れる（トリートメントレジ清算表示用）
// 複数の親契約IDがある場合、contractからin句で契約コースを取得する
if($_POST['pid']) $pid_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and pid in (".addslashes($_POST['pid']).") and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");//複数契約情報
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
// 現在契約中のコース情報
$all_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and customer_id ='".addslashes($data['customer_id'])."' and (cancel_date >= '".$reservation['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$reservation['hope_date']."'");
if($all_contract){
	$all_contract_count = count($all_contract)-1; //契約数
	$all_times_sum = 0; // コースの回数の合計（初期化）
	for($i=0;$i<=$all_contract_count;$i++){
		$all_course_id[$i]     = $all_contract[$i]['course_id'];    // コースID
		$all_fixed_price[$i]   = $all_contract[$i]['fixed_price'];	// 商品金額
		$all_per_price[$i]     = $all_contract[$i]['unit_price'];	// 消化単価
		$all_times[$i]         = $all_contract[$i]['times'];		// 回数
		$all_r_times[$i]       = $all_contract[$i]['r_times'];		// 消化回数
		// 契約部位の表示（領収書用）
		if($pid_contract[$i]['contract_part']){
			$all_single_part  = array();  // 部位の配列
			$all_single_parts = "";      // 部位（カンマ区切り） 
			$all_single_part  = explode(",", $all_contract[$i]['contract_part']);
			// 部位名を取得する
			foreach ($all_single_part as $key => $value) {
				$all_single_parts[] = $gContractParts[$value];
			}
			$all_contract_part_receipt[$i]  = implode(",", $all_single_parts); // 部位(カンマ区切り)
		}
	}
}

// オプション金額・割引(複数親契約IDの割引額を合算) 表示用
$sales_array = Get_Table_Array("sales","*", " WHERE del_flg=0 and customer_id ='".addslashes($reservation['customer_id'])."'"." and reservation_id in (".$reservation['id'].")"); //複数契約情報 契約中のデータのみ取得
if($sales_array){
	$sales_array_count 		= count($sales_array)-1; //契約数
	$discount_sum    		= 0; // 割引金額合計（初期化）
	$option_price_sum 		= 0; // コース金額現金合計（初期化）
	$option_transfer_sum 	= 0; // コース金額振込合計（初期化）
	$option_card_sum 		= 0; // コース金額カード合計（初期化）
	for($i=0;$i<=$sales_array_count;$i++){
		$r_times_flg          = $sales_array[0]['r_times_flg'];		    // 消化済みフラグ
		$option_name          = $sales_array[0]['option_name'];		    // オプション（プルダウン）
		$option_price_sum    += $sales_array[$i]['option_price'];	// オプション金額合計（現金）
		$option_transfer_sum += $sales_array[$i]['option_transfer'];// オプション金額合計（振込）
		$option_card_sum     += $sales_array[$i]['option_card'];	// オプション金額合計（カード）
		$fixed_price_sum     += $sales_array[$i]['fixed_price'];	    // コース金額合計   
		$discount_sum        += $sales_array[$i]['discount'];		    // 割引金額合計
		$balance_sum         += $sales_array[$i]['balance'];		    // 売掛金合計
	}
	// 領収書表示用(オプション金額)
	$option_price_receipt = $option_price_sum + $option_transfer_sum + $option_card_sum;
}

//消化単価 20160125 表示の仕方考える
// $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($data['course_id'])."'");
//$per_price = $contract_p['times'] ? round( ($contract_p['fixed_price']-$contrac_p['discount'])/$times_sum) : 0;
//if($course['type'] && $data['r_times']>2) $per_price = round($contract_p['fixed_price']/$times_sum); //2以上だったらパック

//店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// $mensdb = changedb();

//tax
if($data['hope_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}else{
	$tax = Get_Table_Row("basic"," WHERE id = 1");
	$tax2 = 1+$tax['value'];
}


//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
$course_list[0] = "-";
$course_price[0] = "0";
$course_times[0] = "0";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_price[] = round(($result['price'] * (1+$tax['value'])),0);//税込
	$course_times[] = $result['times'];

}

//JSに渡すため、配列を文字列化----------------------------------------------------------------------------
$course_prices = implode(",",$course_price);

$shop_address = str_replace("　", " ", $shop['address']);//全角から半角へ
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

// 領収書
$mpdf_detail = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_pref=".$gPref[$shop['pref']];
$mpdf_detail.= "&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".$customer['name'];
$mpdf_detail.= "&discount=".$discount_sum."&payment=". $sales['payment'];
if($option_name){
	$mpdf_detail.= "&option_name=".$gOption[$option_name];
} else {
	$mpdf_detail.= "&option_name=".($sales['option_name'] ? $gOption[$sales['option_name']] : "");
}
$mpdf_detail.= "&option_price=".$option_price_receipt."&balance=".$balance_sum;
// 売掛回収を行う場合、親契約ID(契約番号)が同じコースのみの領収書を発行する
if($reservation['type'] ==7 ){
	// 売掛回収 OR 売掛回収/トリートメント時
	$mpdf_detail.= "&course_name=".$course_list[$course_id[0]]."&fixed_price=".$fixed_price[0]."&contract_part=".$contract_part_receipt[0];
	$mpdf_detail.= "&times=".$times[0]."&r_times=".$r_times[0]."&per_price=".$per_price[0];
	// 複数コース分
	if($fixed_price[1]){
		$mpdf_detail.= "&course_name2=".$course_list[$course_id[1]]."&fixed_price2=".$fixed_price[1]."&contract_part2=".$contract_part_receipt[1];
		$mpdf_detail.= "&times2=".$times[1]."&r_times2=".$r_times[1]."&per_price2=".$per_price[1];
	}
	if($fixed_price[2]){
		$mpdf_detail.= "&course_name3=".$course_list[$course_id[2]]."&fixed_price3=".$fixed_price[2]."&contract_part3=".$contract_part_receipt[2];
		$mpdf_detail.= "&times3=".$times[2]."&r_times3=".$r_times[2]."&per_price3=".$per_price[2];
	}
	if($fixed_price[3]){
		$mpdf_detail.= "&course_name4=".$course_list[$course_id[3]]."&fixed_price4=".$fixed_price[3]."&contract_part4=".$contract_part_receipt[3];
		$mpdf_detail.= "&times4=".$times[3]."&r_times4=".$r_times[3]."&per_price4=".$per_price[3];
	}
} else {
	// トリートメントレジ清算（複数コースOK）
	$mpdf_detail.= "&course_name=".$course_list[$all_course_id[0]]."&fixed_price=".$all_fixed_price[0]."&contract_part=".$all_contract_part_receipt[0];
$mpdf_detail.= "&times=".$all_times[0]."&r_times=".$all_r_times[0]."&per_price=".$all_per_price[0];
// 複数コース分
if($all_fixed_price[1]){
		$mpdf_detail.= "&course_name2=".$course_list[$all_course_id[1]]."&fixed_price2=".$all_fixed_price[1]."&contract_part2=".$all_contract_part_receipt[1];
	$mpdf_detail.= "&times2=".$all_times[1]."&r_times2=".$all_r_times[1]."&per_price2=".$all_per_price[1];
}
if($all_fixed_price[2]){
		$mpdf_detail.= "&course_name3=".$course_list[$all_course_id[2]]."&fixed_price3=".$all_fixed_price[2]."&contract_part3=".$all_contract_part_receipt[2];
	$mpdf_detail.= "&times3=".$all_times[2]."&r_times3=".$all_r_times[2]."&per_price3=".$all_per_price[2];
}
	if($all_fixed_price[3]){
		$mpdf_detail.= "&course_name4=".$course_list[$all_course_id[3]]."&fixed_price4=".$all_fixed_price[3]."&contract_part4=".$all_contract_part_receipt[3];
	$mpdf_detail.= "&times4=".$all_times[3]."&r_times4=".$all_r_times[3]."&per_price4=".$all_per_price[3];
	}

}
// 売掛回収時のpid残金
$mpdf_detail.= "&price=".$sales['price']; // 値引後金額合計
// 売掛回収 OR 売掛回収/トリートメント時
// 契約残金を払うときのコース名
if($reservation['type'] ==27){
	if($fixed_price[0])$mpdf_detail.= "&contract_course_name=".$course_list[$course_id[0]];
	if($fixed_price[1])$mpdf_detail.= "&contract_course_name2=".$course_list[$course_id[1]];
	if($fixed_price[2])$mpdf_detail.= "&contract_course_name3=".$course_list[$course_id[2]];
	if($fixed_price[3])$mpdf_detail.= "&contract_course_name4=".$course_list[$course_id[3]];
}

$point = $sales['point'] ? $sales['point'] : $data['point'];

?>
