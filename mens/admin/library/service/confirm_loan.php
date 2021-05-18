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

$c_table = "contract";
$p_table = "contract_P";

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['pid'] != "" )  {
	$contract_p = Get_Table_Row($p_table," WHERE del_flg=0 and id = '".addslashes($_POST['pid'] )."'");
	$pid_contract = Get_Table_Array($c_table,"*"," WHERE del_flg=0 and pid = '".addslashes($contract_p['id'])."'");	
	//$data = Get_Table_Row($c_table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($contract_p['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($contract_p['shop_id'])."'");
	if($contract_p['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($contract_p['sales_id'])."'");

}elseif( $_POST['customer_id'] != "" )  {
	$contract_p = Get_Table_Row($p_table," WHERE (status=0 or status=5) and del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$pid_contract = Get_Table_Array($c_table,"*"," WHERE del_flg=0 and pid = '".addslashes($contract_p['id'])."'");	
	//$data = Get_Table_Row($c_table," WHERE (status=0 or status=5) and del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($contract_p['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($contract_p['shop_id'])."'");
	if($contract_p['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($contract_p['sales_id'])."'");
}

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	//if(!$_POST['loan_date'] || $_POST['loan_date']>date('Y-m-d') || $data['loan_date']<>"0000-00-00" && $data['loan_date']<date('Y-m-d') && $data['loan_status']==1 ){
	if(!$_POST['loan_date'] || $_POST['loan_date']>date('Y-m-d')  ){
		$gMsg  = "※ローン処理日を入力してください。";
		if($_POST['loan_date']>date('Y-m-d')  )$gMsg = "※未来日にローン処理できません。";
		//$gMsg .= "<font color='red' size='-1'>※過去変処理できません。</font>";

		if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsg ); //ローン一覧へ
		else header( "Location: ../reservation/edit.php?id=".$_POST['reservation_id']."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']."&gMsg=".$gMsg ); 				//予約詳細へ
	}else{
		$_POST['edit_date'] = date('Y-m-d H:i:s');

		// ローン承認済み、売掛金残金なし
		if($_POST['loan_status']==1 && $contract_p['balance']<=0){
			$_POST['pay_complete_date'] = $_POST['loan_date']; // 支払完了日
		}

		// 共通項目のセット
		$_POST['customer_id']       = $contract_p['customer_id'];
		$_POST['shop_id'] 		    = $contract_p['shop_id'];
		$_POST['staff_id']          = $contract_p['staff_id'];
		$_POST['contract_date']     = $contract_p['contract_date'];
		$_POST['memo']              = $contract_p['memo'];

		if($_POST['loan_status'] == 4){ // ローン取消
			$_POST['cancel_date'] = $_POST['loan_date']; // 解約日
			$_POST['pay_complete_date'] = "0000-00-00";  // 支払完了日
		}

		if($_POST['loan_status'] == 2) { //ローン非承認 
			$_POST['pay_complete_date'] = "0000-00-00";  // 支払完了日
		}
		
		// 契約テーブルの更新
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s"); // 登録・更新日時
		foreach ($pid_contract as $key => $contract) {

			// 共通項目のセット
			$_POST['status']            = $contract['status'];
			$_POST['course_id']         = $contract['course_id'];
			$_POST['times']             = $contract['times'];
			$_POST['fixed_price']       = $contract['fixed_price'];
			$_POST['discount']          = $contract['discount'];
			$_POST['price']             = $contract['price'];
			$_POST['payment_cash']      = $contract['payment_cash'];
			$_POST['payment_transfer']  = $contract['payment_transfer'];
			$_POST['payment_card']      = $contract['payment_card'];
			$_POST['payment_coupon']    = $contract['payment_coupon'];
			$_POST['balance']           = $contract['balance'];
			$_POST['latest_date']       = $contract['latest_date'];
			$_POST['r_times']           = $contract['r_times'];
			$_POST['end_date']          = $contract['end_date'];
			$_POST['memo']              = $contract['memo'];
			
			// ローン取消後のプラン変更と支払方法変更のため、旧契約データをコピー
			if($contract['status']==0){ // 契約中

				if($_POST['loan_status'] == 2 || $_POST['loan_status'] == 4){ // ローン非承認 OR ローン取消
					$_POST['payment'] = $contract['payment'] - $contract['payment_loan']; // 入金金額
					$_POST['balance'] = $contract['balance'] + $contract['payment_loan']; // 売掛金
					$_POST['payment_loan'] = 0;											  // 入金金額(ローン)

					// 契約待ち
					if($_POST['loan_status'] == 4) $_POST['status'] = 7; 

					$contract_field  = array("status","sales_id","customer_id","shop_id","staff_id","course_id","times","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_transfer","payment_card","payment_loan","payment_coupon","balance","latest_date","r_times","contract_date","end_date","memo","edit_date");
					$contract_ID = Update_Data($c_table,$contract_field,$contract['id']); // "reservation_id",は更新しない 20160610 shimada

					//予約テーブルにキャンセル日以後のデータが新契約ID反映
					// $GLOBALS['mysqldb']->query("update reservation set contract_id=".$contract_id." where del_flg=0 and customer_id=".$_POST['customer_id']." and hope_date>='".$_POST['cancel_date']."'");

					// $_POST['loan_date'] = $_POST['cancel_date'];
					$contract_field2 = array("status","sales_id","balance","loan_status","loan_date","cancel_date","edit_date","memo");
					
					// 更新
					if($contract['id']) $_POST['contract_id'] = Update_Data($c_table,$contract_field2,$contract['id']);
				
				} else { // ローン承認中 OR ローン承認済の場合、ステータスのみ変更する

					$contract_field2 = array("status","pay_complete_date","loan_status","loan_date" , "edit_date");
					// 更新
					if($contract['id']) $_POST['contract_id'] = Update_Data($c_table,$contract_field2,$contract['id']);
				}
			
			} 
			// elseif($contract['status'] ==5){ // ローン取消

			// 	//売掛金
			// 	$_POST['balance'] = $contract['price']-( $contract['payment_cash'] + $contract['payment_card'] + $contract['payment_transfer']); 
			// 	$_POST['pay_date'] = $_POST['cancel_date'];
			// 	$_POST['payment'] = $_POST['payment_loan'];

			// 	$contract_ID = Update_Data($c_table,$contract_field,$contract['id']);
			// }
			$course_id[] = $contract['course_id'];
			
		}
		if($course_id)$multiple_course_id = implode(",", $course_id); // 複数コースID

		// 売上・親契約テーブルへの登録用
		$_POST['pid'] = $contract_p['id'];                  // 親契約ID
		$_POST['multiple_course_id'] = $multiple_course_id; // 複数コースID

		// ローン取消の場合、金額計算しなおす
		if($contract_p['status']==0){ // 契約中
			
			if($_POST['loan_status'] == 2 || $_POST['loan_status'] == 4){// ローン非承認 OR ローン取消

				// sales.typeにステータスを追加
				if($_POST['loan_status'] == 2){
					$_POST['type'] = 15; // ローン非承認
				} elseif($_POST['loan_status'] == 4){
					$_POST['type'] = 9;  // ローン取消
				}
				
				// 売上テーブルへ登録する売掛金の再計算をする
				$_POST['price']         = $contract_p['price'];
				$_POST['fixed_price']   = $contract_p['fixed_price'];
				$_POST['discount']      = $contract_p['discount'];
				$_POST['balance']       = $contract_p['price']-( $contract_p['payment_cash'] + $contract_p['payment_card'] + $contract_p['payment_transfer']); 
				$_POST['pay_date']      = $_POST['cancel_date'];
				$_POST['payment_loan']  = 0 -$contract_p['payment_loan'];	// 入金金額(ローン)
				$_POST['payment']       = $_POST['payment_loan'];	    // 入金金額

				// 契約待ち(contract_Pへ登録用)
				if($_POST['loan_status'] == 4) $_POST['status'] = 7; 

				$sales_field  = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","fixed_price","discount","price","payment","payment_loan","balance","memo","pay_date","cancel_date","reg_date","edit_date");
				$sales_field2 = array("pid","contract_id","type","customer_id","shop_id","staff_id","rstaff_id","multiple_course_id","fixed_price","discount","price","payment","payment_loan","balance","memo","pay_date","cancel_date","edit_date");

				// 売上tableに反映---------------------------------------------------------------
				// 再度精算、前回精算の取り消し
				if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);
				else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）
			}
		}

		// 親契約への登録用
		$_POST['fixed_price']      = $contract_p['fixed_price'];
		$_POST['discount']         = $contract_p['discount'];
		$_POST['price']            = $contract_p['price'];
		$_POST['payment_cash']     = $contract_p['payment_cash'];
		$_POST['payment_transfer'] = $contract_p['payment_transfer'];
		$_POST['payment_card']     = $contract_p['payment_card'];
		$_POST['payment_coupon']   = $contract_p['payment_coupon'];
		$_POST['balance']          = $contract_p['balance'];
		$_POST['fixed_price']      = $contract_p['fixed_price']; 	
		
		// 親契約テーブルの更新
		// ローン取消の場合、金額計算したデータを更新する。それ以外は金額以外を変更する
		$_POST['id'] = $_POST['pid'];	// 親契約ID
		if($_POST['loan_status'] == 2 || $_POST['loan_status'] == 4){// ローン非承認 OR ローン取消

			// 親契約テーブルへセットする金額を再計算する
			$_POST['payment'] = $contract_p['payment'] - $contract_p['payment_loan']; // 入金金額
			$_POST['balance'] = $contract_p['balance'] + $contract_p['payment_loan']; // 売掛金
			$_POST['payment_loan'] = 0;

			$contract_p_field = array("status","loan_status","loan_date","sales_id","shop_id","staff_id","pay_complete_date","fixed_price","discount","price","payment","payment_cash","payment_transfer","payment_card","payment_loan","payment_coupon","balance","contract_date","end_date","cancel_date","memo","edit_date");
			$contract_p_ID = Update_Data($p_table,$contract_p_field,$_POST['id']);
		} else {
			$contract_p_field2 = array("status","pay_complete_date","loan_status","loan_date" , "edit_date");
			$contract_p_ID = Update_Data($p_table,$contract_p_field2,$_POST['id']);
		}
	
		// 親契約テーブルエラーチェック
		if( $contract_p_ID ) 	{
			if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsgv ); //ローン一覧へ
			else header( "Location: ../reservation/edit.php?id=".$_POST['reservation_id']."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']."&gMsg=".$gMsg ); 				//予約詳細へ
		}else {
			$gMsg = 'エラーが発生しました。';
			if($_POST['mode'])	header( "Location: ../account/loan.php?shop_id=".$_POST['shop_id']."&start=".$_POST['start']."&contract_date=".$_POST['contract_date']."&contract_date2=".$_POST['contract_date2']."&status=".$_POST['status']."&line_max=".$_POST['line_max']."&gMsg=".$gMsg ); //ローン一覧へ
			else header( "Location: ../reservation/edit.php?id=".$_POST['reservation_id']."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']."&gMsg=".$gMsg ); 				//予約詳細へ
		}
	}
}

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();


//courseリスト
$course_list  = getDatalistMens("course");

?>
