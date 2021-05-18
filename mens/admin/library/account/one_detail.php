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
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//reservation.idが基準----------------------------------------------------------------------------

//契約時点のレジ精算と残金のレジ精算
//精算した金額、コース名を売上テーブルsalesに格納
//売上合計、分析の際、どのテーブルからデータ取得？
//mysqlの高速化とtable数。ただ、(カウンセリング予約数+施術予約数＞レジ精算数)ので、salesテーブルが必要

//既存会員の契約新規処理？
//customer.reg_flg=0;契約新規時、レジ精算前
//customer.reg_flg=1;契約新規時、レジ精算済（初回支払）

//レジ担当=>reservation?sales?

//契約：初回新規、プラン変更より新規、全契約期間終了より新規、該当契約の役務消化（残る回数、売掛金）、キャンセル、返金
//契約期間：二年

//１．初回新規契約と初回新規契約の変更
//２．区分により施術精算の売掛金、役務消化回数の計上
//３．値引き：数字　と　%

$table = "reservation";

//編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	if( $_POST['id']) $reservation = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	//if( $_POST['id']) $contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and reservation_id = '".addslashes($_POST['id'])."'"); // 親契約table
	if( $reservation['contract_id']) $contract_date = Get_Table_Col("contract","contract_date"," WHERE del_flg=0 and id = '".addslashes($reservation['contract_id'])."'");

	$editable_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-0, date("Y")));
	$editable_date2 = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	if(!$_POST['id']){
		$gMsg = "<font color='red' size='-1'>※予約自体が未登録です。</font>";

	}elseif($authority_level>6 && $reservation['hope_date']<$editable_date){
		$gMsg = "<font color='red' size='-1'>※本日前のデータが編集不可です。</font>";

	}elseif($authority_level>=1 && $authority_level<=6 && $reservation['hope_date']<$editable_date2){
		$gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";

	}elseif($authority_level>=1 && $reservation['course_id'] && $reservation['hope_date']<>$contract_date && $_POST['course_id']<>$reservation['course_id']){
		$gMsg = "<span style='color:red;font-size:13px;'>※レジ精算でプラン変更できません。</span>";

	}elseif($reservation['hope_date']>date('Y-m-d')){
		$gMsg = "<font color='red' size='-1'>※未来日にレジ精算できません。</font>";

	}elseif($authority_level>1 && !$_POST['cstaff_id']){
		$gMsg = "<font color='red' size='-1'>※カウンセリング担当を選択してください。</font>";

	}elseif($authority_level>6 && !$_POST['rstaff_id']){
		$gMsg = "<font color='red' size='-1'>※レジ担当を選択してください。</font>";

	// コース未選択確認----------------
	}elseif(!$_POST['fixed_price'] && !$_POST['single_fixed_price']){ // 単発コース金額・単発部位金額どちらも選択がない

		//document.form1.payment.value = price; // 初回入金

		if($reservation['reg_flg'] ){

			$sql = "UPDATE ".$table." SET reg_flg = 0,course_id=0,contract_id=0,r_times=0,sales_id=0";
			$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
//			$dRes = $GLOBALS['mysqldb']->query($sql);

	//明細--------------------------------------------------------------------------------
			$sql = "UPDATE contract SET del_flg = 1";
			$sql .= " WHERE customer_id = '".addslashes($reservation['customer_id'])."' and contract_date>='".$reservation['hope_date']."'";
//			$dRes = $GLOBALS['mysqldb']->query($sql);

			// 売上データ仮削除
			$sql = "UPDATE sales SET del_flg = 1";
			$sql .= " WHERE id = '".addslashes($reservation['sales_id'])."'";
//			$dRes = $GLOBALS['mysqldb']->query($sql);

			$gMsg = "<font color='red' size='-1'>※削除が完了しました。</font>";

		}else{
				$gMsg = "<font color='red' size='-1'>※コースが未選択でした。1つは選択してください。</font>";
		}
	}else{

		//データ取得--------------------------------------------------------------------------------

		if( $_POST['course_id'] )  	 $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($_POST['course_id'])."'");
		//契約詳細取得-----------------------------------------------------------------------------
		if($reservation['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($reservation['contract_id'])."'");
		else $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and customer_id = '".addslashes($reservation['customer_id'] )."' and del_flg=0 and (status=0 or status=7) and end_date>='".date("Y-m-d")."'");

		//POSTに格納------------------------------------------------------------------------------
		$_POST['contract_date'] = $_POST['pay_date'] = $reservation['hope_date'];  // 契約日

		// 本日の入金金額
		$payment           = $_POST['payment_cash']+$_POST['payment_card']+$_POST['payment_transfer']+$_POST['payment_loan'];   // 入金金額(すべて)
		$payment_cash      = $_POST['payment_cash'];																			// 入金金額(現金)
		$payment_card      = $_POST['payment_card'];																			// 入金金額(カード)
		$price             = $_POST['price'];																					// 請求金額(割引後)
		$discount_sum      = $_POST['discount']+$_POST['discount2']+$_POST['discount3']+$_POST['single_discount']; 				// 割引金額合計
		$fixed_price_sum = $_POST['fixed_price'] +  $_POST['fixed_price2'] +  $_POST['fixed_price3'] +  $_POST['single_fixed_price'];// コース金額合計

		//下記の場合、契約ステータスを1.契約終了で登録する
		//	役務消化にチェックが入っていたら
		//	契約テーブルの消化回数が1回
		if($_POST['if_service'] =="1" || $contract['r_times'] == "1"){
			$_POST['status']  = 1;	// 契約終了
			$_POST['r_times'] = 1;	// 1回の消化完了
			$_POST['r_times_flg'] = 1; // 消化フラグ
			$_POST['latest_date'] = $reservation['hope_date']; // 最終消化日
			$_POST['cancel_date'] = $reservation['hope_date']; // 解約日(契約終了日)
		} else {
			$_POST['status'] = 0;	// 契約中
			$_POST['r_times']= 0;	// 1回の消化完了
			$_POST['r_times_flg'] = 0; // 消化フラグ
			$_POST['latest_date'] = "0000-00-00"; // 最終消化日
			$_POST['cancel_date'] = "0000-00-00"; // 解約日(契約終了日)
		}

		$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] ;      // 支払金額＝現金+カード
		// $_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card']  + $_POST['payment_transfer'] + $_POST['payment_loan'] + $_POST['payment_coupon']; //支払金額＝現金+カード+振込+ローン+クーポン
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		$_POST['reservation_id'] = $_POST['id'];
		$_POST['times'] = $course['times'];
		$_POST['reg_flg'] = 1;
		$_POST['staff_id'] = $_POST['cstaff_id'] ? $_POST['cstaff_id'] : $_POST['tstaff_id'];
		//$_POST['price'] = $fixed_price_sum - $discount_sum; //値引き後コース金額
		$_POST['balance'] = $_POST['price'] - $payment; //売掛金

		//契約tableに反映------------------------------------------------------------------------
		//新規のみ、他の日で残金回収の可能性があり、編集の場合上書き防止

		//新規のみ、他の日で残金回収の可能性があり、編集の場合上書き防止
		if($_POST['balance']<=0 && !$_POST['payment_loan']) $_POST['pay_complete_date']=$_POST['contract_date'];

		//「支払完了日」記入有、掛けが0円以下、ローン承認済み　の場合、支払完了日を予約日で上書きする 20151001 shimada
		 //*1回で掛け清算時*
		else if($contract['pay_complete_date'] != "0000-00-00" && $_POST['balance']<=0 && $contract['loan_status'] ==1) $_POST['pay_complete_date']=$reservation['hope_date'];

		//*複数回で掛け清算時*
		else if($contract['pay_complete_date'] != "0000-00-00" && ($contract['balance'] - $_POST['balance'])<=0 && $contract['loan_status'] ==1) $_POST['pay_complete_date']=$reservation['hope_date'];
		else $_POST['pay_complete_date'] = "0000-00-00";


		// 20160119 親契約テーブルを追加
		// 親契約テーブル項目
		$contract_p_field  = array("reservation_id","customer_id","multiple_course_id", "shop_id","staff_id","status","fixed_price","discount","dis_type","price","pay_type","pay_complete_date","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo","contract_date","end_date","cancel_date","reg_date","edit_date");
		$contract_p_field2 = 							 	array("multiple_course_id", "shop_id","staff_id","status","fixed_price","discount","dis_type","price","pay_type","pay_complete_date","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo"			       ,"end_date","cancel_date",			"edit_date");

		// 契約テーブル項目
		// $contract_field  = array("reservation_id","customer_id","shop_id","staff_id","course_id","times","fixed_price","discount","dis_type","price","pay_type","pay_complete_date","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo","contract_date","end_date","reg_date","edit_date");
		// $contract_field2 = 							 	  array("shop_id","staff_id","course_id","times","fixed_price","discount","dis_type","price","pay_type","pay_complete_date","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo",				"end_date","edit_date");
		$contract_field  = array("reservation_id","customer_id","pid","shop_id","staff_id","course_id","status","fixed_price","discount","price","unit_price","surplus_unit_price","dis_type","pay_type","payment","payment_cash","payment_card","times","r_times","contract_part","part_time_sum","memo","contract_date","end_date","cancel_date","latest_date","reg_date","edit_date");
		$contract_field2 = 							 	  		array("shop_id","staff_id","course_id","status","fixed_price","discount","price","unit_price","surplus_unit_price","dis_type","pay_type","payment","payment_cash","payment_card","times","r_times","contract_part","part_time_sum","memo",			       "end_date","cancel_date","latest_date"		    ,"edit_date");

		// ***親契約table***への処理
		// コースIDの「選択なし」データを整形する
		$_POST['course_id']     = ($_POST['course_id']  =="-") ? "" : $_POST['course_id'];
		$_POST['course_id2']    = ($_POST['course_id2'] =="-") ? "" : $_POST['course_id2'];
		$_POST['course_id3']    = ($_POST['course_id3'] =="-") ? "" : $_POST['course_id3'];

		// コースごとの情報（契約テーブルへ登録用）
		$course_data        = array(
				array(	'course_id'     =>$_POST['course_id'],						// コースID
						'fixed_price'   =>$_POST['fixed_price'],					// 商品金額
						'discount'      =>$_POST['discount'],						  // 割引金額
						'price'         =>$_POST['fixed_price']-$_POST['discount'],  // 割引後金額
						'contract_part' =>implode(',', $_POST['contract_parts'])	// 施術部位
					),
				array(	'course_id'     =>$_POST['course_id2'],						// コースID2
						'fixed_price'   =>$_POST['fixed_price2'],					// 商品金額2
						'discount'      =>$_POST['discount2'],
						'price'         =>$_POST['fixed_price2']-$_POST['discount2'],
						'contract_part' =>implode(',', $_POST['contract_parts2'])	// 施術部位2
					),
				array(	'course_id'     =>$_POST['course_id3'],						// コースID3
						'fixed_price'   =>$_POST['fixed_price3'],					// 商品金額3
						'discount'      =>$_POST['discount3'],
						'price'         =>$_POST['fixed_price3']-$_POST['discount3'],
						'contract_part' =>implode(',', $_POST['contract_parts3'])	// 施術部位3
					),
				array(	'course_id'     =>$_POST['single_course_id'],				// 単発ID
						'fixed_price'   =>$_POST['single_fixed_price'],				// 単発金額
						'discount'      =>$_POST['single_discount'],				  // 単発割引
						'price'         =>$_POST['single_fixed_price']-$_POST['single_discount'], // 単発割引後金額
						'contract_part' =>implode(',', $_POST['contract_single'])	// 単発契約部位
					)
			);

		// コースと単発コースをカンマ区切りでまとめて$_POST['multiple_course_id']に格納する
		$_POST['course_id'] = array($_POST['course_id'],$_POST['course_id2'],$_POST['course_id3'],$_POST['single_course_id']);
		$_POST['course_id'] = array_filter($_POST['course_id']);
		// 複数コースID
		$_POST['multiple_course_id'] = implode(',', $_POST['course_id']);
		
		// 複数コース金額を合計した値を$_POST['fixed_price']に格納する
		$_POST['price']               = $price;           	// 請求金額(割引後)
		$_POST['discount']            = $discount_sum;      // 割引金額合計
		$_POST['fixed_price']         = $fixed_price_sum;   // コース金額合計 

		// 売上ID
		$_POST['sales_id'] = $data['sales_id'];

		// 配列に格納した項目をunsetする
		unset($_POST['course_id']);
		unset($_POST['course_id2']);
		unset($_POST['course_id3']);
		unset($_POST['single_course_id']);
		unset($_POST['fixed_price2']);
		unset($_POST['fixed_price3']);
		unset($_POST['single_fixed_price']);
		unset($_POST['discount2']);
		unset($_POST['discount3']);
		unset($_POST['single_discount']);
		unset($_POST['contract_parts']);
		unset($_POST['contract_parts2']);
		unset($_POST['contract_parts3']);
		unset($_POST['contract_single']);
		// 親契約table   更新 or 新規
		if($reservation['pid']) $_POST['pid'] = Update_Data("contract_P",$contract_p_field2,$reservation['pid']);
		else $_POST['pid'] = Input_New_Data("contract_P",$contract_p_field); // 新規

		// ***契約table***への処理
		// 親契約tableの情報取得
		if($_POST['pid']) $contract_p = Get_Table_Row("contract_P"," WHERE del_flg=0 and reservation_id = '".addslashes($_POST['id'])."'");

		// 契約テーブルへ登録する情報を作る
		//   親契約tableの親契約IDがあれば、それに紐づく契約tebleのデータを一旦論理削除し
		//   再度契約tebleのデータだけ作り直す
		if($reservation['pid']){
			// 契約table更新
			$sql = "UPDATE contract SET del_flg = 1";
			$sql .= " WHERE del_flg=0 AND pid = '".addslashes($reservation['pid'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql);

			// 予約テーブルに紐づく消化table更新(作り直すために削除)
			$sql = "UPDATE r_times_history SET del_flg = 1";
			$sql .= " WHERE del_flg=0 AND reservation_id = '".addslashes($reservation['id'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql);
		}
		// 消化単価の計算をするための配列データを作成
		foreach ($course_data as $key => $value) {
			if($value){
				// コース情報を取得
				$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($value['course_id'])."'");
				$fix_price_array[$value['course_id']]   = $value['fixed_price'];     // 商品金額
				$times_array[$value['course_id']]       = $course['times'];          // 回数
				$discount_array[$value['course_id']]    = $value['discount'];        // 割引金額
			}
		}

		// 単発・単発コースの所要時間を計算し、契約テーブルに格納する
		$single_id_array ="";	// 単発部位のid
		foreach ($course_data as $key => $value) {
			if($value['course_id']<>0){
				// 契約毎の合計施術時間
				$part_time_sum = 0;

				// コース情報を取得
				$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($value['course_id'])."'");
				if($value['course_id'] ==49){ // 単発にチェックが入っている場合
					// 選択した単発部位を配列にし、各部位の所要時間を合計する
					$single_id_array = explode(',', $value['contract_part']);
					foreach ($single_id_array as $key => $single_id) {
						// 部位のvalueの値から、courseテーブルのIDを取得し、部位毎の所要時間を足していく
						$part = Get_Table_Row("part"," WHERE del_flg=0 and id = '".addslashes($single_id)."'");
						$part_time_sum += $part['part_length'];	// 各部位の所要時間（分）
					}
				} else {
					// 単発コース
					$part_time_sum = $course['part_length'];	// 単発コースにかかる所要時間(分)
				}
				// 消化単価の計算(消化単価と余りの配列を返す)
				$unit_array  = Unit_Price_Calculation($fix_price_array,$times_array,$discount_array,$payment_cash,$payment_card,0,0);
				// コースごとの入金金額を合算(現金+カード)
				$course_payment     = $unit_array['cash'][$value['course_id']]+$unit_array['card'][$value['course_id']];

				// 登録項目をセット
				$_POST['course_id']      = $value['course_id'];		// コースID
				$_POST['times']          = $course['times'];    	// コース回数
				$_POST['fixed_price']    = $value['fixed_price'];	// 商品金額
				$_POST['discount']      	 = $value['discount'];		// 割引金額
				$_POST['price']         	 = $value['price'];		    // 割引後金額
				$_POST['unit_price']         = $unit_array['unit_price'][$value['course_id']];  // 消化単価
				$_POST['surplus_unit_price'] = $unit_array['surplus'][$value['course_id']];     // 消化単価余剰分
				$_POST['payment']            = $course_payment;                                 // 入金金額(すべて)
				$_POST['payment_cash']       = $unit_array['cash'][$value['course_id']];        // 入金金額(現金)
				$_POST['payment_card']       = $unit_array['card'][$value['course_id']];        // 入金金額(カード)
				$_POST['contract_part']  = $value['contract_part'];	// 契約部位
				$_POST['part_time_sum']  = $part_time_sum;			// コースにかかる所要時間(分)
				// 保証期間(日)から役務提供期間を設定する
				if($course['period']) $_POST['end_date'] = date("Y-m-d",strtotime("+{$course['period']} day",strtotime($_POST['contract_date'])));
				// 契約tableに登録
				$_POST['contract_id'] = Input_New_Data("contract",$contract_field); // 新規

				// 消化履歴tableへ登録するためのデータをセットする
				$contract_value = Get_Table_Row("contract"," WHERE del_flg=0 and pid = ".addslashes($_POST['pid'])." and course_id =".addslashes($value['course_id'])); // 消化後の契約データ
				$_POST['pid']           = $contract_value['pid'];           // 親契約ID
				$_POST['contract_id']   = $contract_value['id'];            // 契約ID
				$_POST['course_id']     = $contract_value['course_id'];     // コースID
				$_POST['contract_part'] = $contract_value['contract_part']; // 施術部位
				$_POST['part_time_sum'] = $contract_value['part_time_sum']; // 施術時間合計
				$_POST['times']         = $contract_value['times'];         // コース回数
				$_POST['fixed_price']   = $contract_value['fixed_price'];   // コース金額
				$_POST['discount']      = $contract_value['discount'];      // 割引
				$_POST['dis_type']      = $contract_value['dis_type'];      // 割引タイプ
				$_POST['price']         = $contract_value['price'];         // 請求金額
				$_POST['unit_price']    = $contract_value['unit_price'];    // 消化単価
				$_POST['status']        = $_POST['status'];                 // 契約ステータス
				$_POST['cancel_date']   = $_POST['cancel_date'];            // 解約日
				// 消化table登録
				$r_times_history_field  = array("pid","contract_id","status","cancel_date","reservation_id","customer_id","shop_id","staff_id","rstaff_id","type","course_id","times","r_times","contract_part","part_time_sum","fixed_price","discount","dis_type","price","unit_price","pay_date","memo","reg_date","edit_date","r_times_flg");
				$_POST['r_times_id'] = Input_New_Data("r_times_history",$r_times_history_field);       // 消化履歴tableへ登録

			}
		}

		//売上tableに反映------------------------------------------------------------------------
		$sales_field  = array("pid","contract_id","type","reservation_id","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","course_id","times","r_times","r_times_flg","fixed_price","discount","dis_type","point","option_name","option_price","option_card","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo","pay_date","cancel_date","reg_date","edit_date");
		$sales_field2 = 													      array("shop_id","staff_id","rstaff_id","multiple_course_id","course_id","times","r_times","r_times_flg","fixed_price","discount","dis_type","point","option_name","option_price","option_card","price","pay_type","payment","payment_cash","payment_card","payment_transfer","payment_loan","payment_coupon","balance","memo","pay_date","cancel_date",			"edit_date");

		// ***売上table***への処理
		$_POST['payment']             = $payment;           // 入金金額(すべて)
		$_POST['payment_cash']        = $payment_cash;    	// 入金金額(現金)
		$_POST['payment_card']        = $payment_card;    	// 入金金額(カード)
		$_POST['price']               = $price;           	// 請求金額(割引後)
		$_POST['discount']            = $discount_sum;      // 割引金額合計
		$_POST['fixed_price'] = $fixed_price_sum; // コース金額合計
		// 売上table   更新 or 新規
		if($reservation['sales_id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$reservation['sales_id']);//再度精算、前回精算の取り消し
		else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

		//予約tableに反映-------------------------------------------------------------------------
		$r_times_history_field2 = array("sales_id");
		if($_POST['sales_id'])Update_Data_Where("r_times_history",$r_times_history_field2,"del_flg=0 and reservation_id = '".addslashes($reservation['id'])."'"); 

		//予約tableに反映-------------------------------------------------------------------------
		$reservation_field = array("pid","contract_id","sales_id","course_id","reg_flg","edit_date","rstaff_id","edit_date");
		if(isset($_POST['cstaff_id'])) array_push($reservation_field,  "cstaff_id");
		if(isset($_POST['tstaff_id'])) array_push($reservation_field,  "tstaff_id", "tstaff_sub1_id", "tstaff_sub2_id");
		// 複数契約IDを作る(予約テーブルへの登録用)
		if($_POST['pid'] !=0 ){
			$contract_array = Get_Table_Array("contract","*"," WHERE del_flg=0 and pid = '".addslashes($_POST['pid'])."'");
			foreach ($contract_array as $key => $value) {
				$multiple_contract_id[] = $value['id']; // 契約ID
			}
			$multiple_contract_id = implode(",", $multiple_contract_id); // 複数契約ID
			array_push($reservation_field,  "multiple_contract_id");
			$_POST['multiple_contract_id'] = $multiple_contract_id;
		} 

		// 更新
		$data_ID = Update_Data($table ,$reservation_field,$_POST['id']);

		//Msg----------------------------------------------------------------------------

		if( $data_ID ) {
			$gMsg = '（登録完了）';
			$complete_flg = 1;
		}else $gMsg = '（登録しませんでした。)';
	}
}

// 詳細を取得----------------------------------------------------------------------------

if( $_POST['id'] )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
	if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
	if($data['pid']) $all_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and reservation_id = '".addslashes($_POST['id'])."'"); //複数契約情報
	}
	// 契約数からコースを取得し、配列に入れる（単発レジ清算表示用）
	if($all_contract){
		$all_contract_count = count($all_contract)-1; //契約数
		for($i=0;$i<=$all_contract_count;$i++){
			// 部位単発選択時
			if($all_contract[$i]['course_id'] ==49){
				$single_course_id   = $all_contract[$i]['course_id'];		// コースID(単発)
				$single_fixed_price = $all_contract[$i]['fixed_price'];		// コース金額(単発)
				$single_part        = $all_contract[$i]['contract_part'];	// 契約部位のチェック項目(単発)
				$single_discount    = $all_contract[$i]['discount'];	    // 割引金額(単発)
			} else {
			// 単発コース選択時
				$course_id[$i]     = $all_contract[$i]['course_id'];		// コースID
				$fixed_price[$i]   = $all_contract[$i]['fixed_price'];		// コース金額
				$contract_part[$i] = $all_contract[$i]['contract_part'];	// 契約部位のチェック項目
				$discount[$i]      = $all_contract[$i]['discount'];	        // 割引金額(コース)
			}
			// 契約部位の表示（領収書用）
			// ※単発コース/単発の情報をまとめて配列に格納する
			$all_course_id[$i]        = $all_contract[$i]['course_id'];
			$all_fixed_price[$i]      = $all_contract[$i]['fixed_price'];
			$all_discount[$i]     = $all_contract[$i]['discount'];		// 割引金額
			if($all_contract[$i]['contract_part']){
				$all_part  = array();  // 部位の配列
				$all_parts = "";      // 部位（カンマ区切り）
				$all_part  = explode(",", $all_contract[$i]['contract_part']);
				// 部位名を取得する
				foreach ($all_part as $key => $value) {
					$all_parts[] = $gContractParts[$value];
				}
				$contract_part_receipt[$i]  = implode(",", $all_parts); // 部位(カンマ区切り)
			}
		}
	}

//店舗リスト----------------------------------------------------------------------------

$shop_list = getDatalist_shop();

//staff list
if($contract['id'])$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$contract['contract_date']."')".$where_shop." ORDER BY id" );
else $staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax
if($data['hope_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
	}else{
	$tax_data = Get_Table_Row("basic"," WHERE id = 1");
	$tax =$tax_data['value'];
	$tax =$tax_data['value'];
	$tax2 = 1+$tax_data['value'];
	}

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 AND old_flg=0 AND one_flg=1 AND 30 <= part_length ORDER BY id" );
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
            //全角数値なら
	$course_price[] = round(($result['price'] * $tax2),0);
	$course_name[] = $result['name'];

}
//courseリスト（カスタマイズ部位）
$course_sql = $GLOBALS['mysqldb']->query( "select * from part WHERE del_flg = 0 AND status=2 AND old_flg=0 ORDER BY id" );
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$part_course_list[$result['id']] = $result['name'];
     //全角数値なら
	$part_course_prices[$result['id']] = round(($result['price'] * $tax2),0);
	$part_course_name[] = $result['name'];
}
// json化
$part_course_prices = json_encode($part_course_prices);
$part_course_names = json_encode($part_course_list);

//全角ハイフンなら
$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);

$option_fix=$sales['option_price']+$sales['option_card']; // オプション支払

            //全角数値なら
$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

            //全角ハイフンなら
$mpdf_one_detail = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_pref=".$gPref[$shop['pref']];
$mpdf_one_detail.= "&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel'];
$mpdf_one_detail.= "&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] );
$mpdf_one_detail.= "&course_name=".$course_list[$all_course_id[0]]."&tax=".$tax."&tax2=".$tax2;
$mpdf_one_detail.= "&fixed_price=".$all_fixed_price[0]."&contract_part=".$contract_part_receipt[0];
$mpdf_one_detail.= "&discount=".$sales['discount']."&price=".$sales['price']."&payment=".$sales['payment'];
$mpdf_one_detail.= "&option_name=".($sales['option_name'] ? $gOption[$sales['option_name']] : "オプション")."&option_price=".$option_fix."&balance=".$sales['balance'];
// 複数コース分
if($all_fixed_price[1])$mpdf_one_detail.= "&course_name2=".$course_list[$all_course_id[1]]."&fixed_price2=".$all_fixed_price[1]."&contract_part2=".$contract_part_receipt[1];
if($all_fixed_price[2])$mpdf_one_detail.= "&course_name3=".$course_list[$all_course_id[2]]."&fixed_price3=".$all_fixed_price[2]."&contract_part3=".$contract_part_receipt[2];
if($all_fixed_price[3])$mpdf_one_detail.= "&course_name4=".$course_list[$all_course_id[3]]."&fixed_price4=".$all_fixed_price[3]."&contract_part4=".$contract_part_receipt[3];


if($data['hp_flg']) $hp_price = 10450;
if($data['hp_flg']==2) $hp_discount = 730;

?>
