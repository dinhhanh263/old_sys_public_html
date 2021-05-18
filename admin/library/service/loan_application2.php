<?php
// ライフティシステム連携

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

$table = "loan_info2";

// 名前整形
if($_POST['name'])		SpaceIntoName('name');
if($_POST['name_kana'])	SpaceIntoName('name_kana');

// 電話番号整形
if($_POST['tel'])$_POST['tel'] = sepalate_tel($_POST['tel']);

//住所に改行をなくす
if($_POST['address'])$_POST['address'] = str_replace("\r\n", '',$_POST['address']);

// 支払初月（年）
if(date("m")==12){
	$year = date("Y")+1;
	$array_first_payment_year[$year] = $year;
}else{
	$array_first_payment_year = array(date('Y') => date('Y'));
}
// 支払初月（月）
$array_first_payment_month[] = "-";
if(date('n')<=10){
	for($month=date('n')+1;$month<=date('n')+2;$month++ ){
		$array_first_payment_month[$month] = $month;
	}
}elseif(date('n')==11){
   	$array_first_payment_month[12] = 12;
}elseif(date('n')==12){
   	$array_first_payment_month[1] = 1;
}

// 編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	$gMsg = validate();
	if( empty( $gMsg  ) ){
		if($_POST['first_payment_year'] && $_POST['first_payment_month'] && $_POST['number_of_payments']){
			$first_payment_ymd= strtotime($_POST['first_payment_year']."-".$_POST['first_payment_month'])."-1";
			$expected_end_ym = date('Y-n', strtotime('+'.($_POST['number_of_payments']-1).' month', $first_payment_ymd));
			$_POST['expected_end_year']= substr($expected_end_ym,0,4);
			$_POST['expected_end_month']= substr($expected_end_ym,5);
		}

		$common_field = array(
			'customer_id',
			'contract_id',
			'course_id',
			'loan_company_id',
			'shop_id',
			'staff_id',
			'application_date',
			'initial_payment',
			'amount',
			'number_of_payments',
			'first_payment_year',
			'first_payment_month',
			'expected_end_year',
			'expected_end_month',
			//'transfer_status',
			'total_installment_commission',
			'amount_of_installments',
			'installment_amount_1st',
			'installment_amount_2nd',
			'annual_amount',
			'service_start',
			'service_end',
			'save_amount',
			'side_job',
			'side_income',
			'payment_lent'
			//'house_type',
			//'living_grant',
			//'same_living_count',
			//'annual_income',
			//'identification_type',
			//'identification_number',
			//'call_timezone',
			//'credit_app_agree',
			//'privacy_agree'
		);

		if($authority_level==0 || $_POST['application_date']==date('Y-m-d')){
			array_push($common_field, 'name','name_kana','mail','tel','birthday','zip','pref','address');
		}

		// 契約レコード取得
		if($_POST['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($_POST['contract_id'])."'");
		else 						 $contract = array();

		// 更新
		if($_POST['id']){
			$_POST['edit_date'] = date('Y-m-d H:i:s');
			array_push($common_field, 'edit_date');
			$_POST['id'] = Update_Data($table,$common_field,$_POST['id']);
		}
		// 新規,リロード防止
		elseif(!Get_Table_Col($table,"id"," WHERE del_flg=0 AND customer_id = '".addslashes($contract['customer_id'])."' AND contract_id = '".addslashes($_REQUEST['contract_id'])."'")){
			$_POST['id'] = Input_New_Data($table,$common_field);
		}

		// 個人情報更新
		if($contract['customer_id'] ){
			$customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($contract['customer_id'])."'");
			if($_POST['name'] <> $customer['name'] ||
			   $_POST['name_kana'] <> $customer['name_kana'] ||
			   $_POST['tel'] <> $customer['tel'] ||
			   $_POST['mail'] <> $customer['mail'] ||
			   $_POST['birthday'] <> $customer['birthday'] ||
			   $_POST['zip'] <> $customer['zip'] ||
			   $_POST['pref'] <> $customer['pref'] ||
			   $_POST['address'] <> $customer['address']
			){
				$_POST['edit_date'] = date('Y-m-d H:i:s');
				$customer_field = array(
					'name',
					'name_kana',
					'mail',
					'tel',
					'birthday',
					'zip',
					'pref',
					'address',
					'edit_date'
				);
				Update_Data('customer',$customer_field,$contract['customer_id']);

				$sheet_id = Get_Table_Col("sheet","id"," WHERE del_flg=0 AND customer_id = '".addslashes($contract['customer_id'])."'");
				if($sheet_id) Update_Data('sheet',$customer_field,$sheet_id);
			}
		}

		// Msg----------------------------------------------------------------------------
		if( $_POST['id'] ) {
			header('Location: ./loan_application2_complete.php?id='.$_POST['id']);
			exit;
		}
	}
}

// 詳細を取得----------------------------------------------------------------------------
if($_REQUEST['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['contract_id'])."'");
else 						 $contract = array();

if($contract['id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($contract['customer_id'])."'");
else 						 $customer = array();

if($contract['id']) 		 $course   = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($contract['course_id'])."'");
else 						 $course   = array();

if($_REQUEST['contract_id']){
	$data = Get_Table_Row($table," WHERE del_flg=0 AND customer_id = '".addslashes($contract['customer_id'])."' AND contract_id = '".addslashes($_REQUEST['contract_id'])."'");
}
else $data = array();

if($data['shop_id']) $shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
else 				 $shop = array();

if($data['staff_id']) $staff = Get_Table_Col("staff","name"," WHERE del_flg=0 AND id = '".addslashes($data['staff_id'])."'");
else 				 $staff = '';

// 支払初月（年）
if($data['first_payment_year'] && $data['first_payment_year']< date('Y') ){
	$array_first_payment_year = array();
	$array_first_payment_year[$data['first_payment_year']] = $data['first_payment_year'];
}
// 支払初月（月）
if($data['first_payment_year'] && $data['first_payment_month'] && ($data['first_payment_year']< date('Y') || $data['first_payment_year']== date('Y') && $data['first_payment_month']< date('n') ) ){
	$array_first_payment_year = array();
	$array_first_payment_year[$data['first_payment_year']] = $data['first_payment_year'];
	$array_first_payment_month = array();
	$array_first_payment_month[$data['first_payment_month']] = $data['first_payment_month'];
}
// 支払初月,システム権限なら全開放
if(!$authority_level){
	$array_first_payment_year = array();
	$array_first_payment_year[] = "-";
	$max_year = date('Y')+1;
	for($i = 2016; $i <= $max_year; $i++){
 	   $array_first_payment_year[$i] = $i;
	}

	$array_first_payment_month = array();
	$array_first_payment_month[] = "-";
	for($i = 1; $i <= 12; $i++){
 	   $array_first_payment_month[$i] = $i;
	}
}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company");
$loan_company_id = isset($data['loan_company_id']) ? $data['loan_company_id'] : $contract['loan_company_id'];

// 申込店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);
$shop_id = $_POST['shop_id'] ? $_POST['shop_id'] : ($data['shop_id'] ? $data['shop_id'] : $contract['shop_id']);

list($name1,$name2) = explode("　", $customer['name']);
list($name_kana1,$name_kana2) = explode("　", $customer['name_kana']);

// デフォルト担当者がカウンセリング契約担当者
if(!isset($_POST['staff_id']) && !isset($data['staff_id'])) $_POST['staff_id'] = $contract['staff_id'];

// デフォルト家賃負担が無
//if(!isset($_POST['payment_lent']) && !isset($data['payment_lent'])) $_POST['payment_lent'] = 2;

$service_start = ($data['service_start'] && $data['service_start']<>'0000-00-00') ? $data['service_start'] : $contract['contract_date'];
$service_end =   ($data['service_end']   && $data['service_end']  <>'0000-00-00') ? $data['service_end']   : $contract['end_date'];

// 商品金額
$price = ($data['amount'] && $data['initial_payment']) ? ($data['amount'] + $data['initial_payment']) : $contract['price'];
// 頭金
$initial_payment = $data['initial_payment'] ? $data['initial_payment'] : ($contract['price']-$contract['payment_loan']-$contract['balance']);
// 申込金額
$payment_loan = $data['amount'] ? $data['amount'] : $contract['payment_loan'];
// 支払総額
$total_amount = $data['initial_payment'] ? ($data['initial_payment']+$data['amount_of_installments']) : ($contract['price']-$contract['balance']+$data['total_installment_commission']);

// 申込PDF用----------------------------------------------------------------------------
if($data['id']){

	$pdf_param =
		"?shop_name=".$shop['name'].
		"&shop_zip=".$shop['zip'].
		"&shop_address=".$gPref[$shop['pref']].$shop['address'].
		"&staff=".$staff.
		"&application_date=".$data['application_date'].
		"&loan_company_name=".$loan_company_list[$data['loan_company_id']].
		"&no=".$customer['no'].
		"&name=".$customer['name'].
		"&name1=".$name1.
		"&name2=".$name2.
		"&name_kana=".$customer['name_kana'].
		"&name_kana1=".$name_kana1.
		"&name_kana2=".$name_kana2.
		"&mail=".$customer['mail'].
		"&tel=".$customer['tel'].
		"&birthday=".$customer['birthday'].
		"&zip=".$customer['zip'].
		"&address=".$gPref[$customer['pref']].$customer['address'].
		"&course_name=".$course['name'].
		"&times=".$course['times'].
		"&price=".$price.
		"&initial_payment=".$initial_payment.
		"&payment_loan=".$payment_loan.
		"&contract_date=".$contract['contract_date'].
		"&contract_date_year=".substr($contract['contract_date'],0,4).
		"&contract_date_month=".substr($contract['contract_date'],5,2).
		"&end_date_year=".substr($contract['end_date'],0,4).
		"&end_date_month=".substr($contract['end_date'],5,2).
		"&number_of_payments=".$data['number_of_payments'].
		"&first_payment_year=".$data['first_payment_year'].
		"&first_payment_month=".$data['first_payment_month'].
		"&expected_end_year=".$data['expected_end_year'].
		"&expected_end_month=".$data['expected_end_month'].
		//"&transfer_status=".$array_transfer_status[$data['transfer_status']].
		"&total_installment_commission=".$data['total_installment_commission'].
		"&amount_of_installments=".$data['amount_of_installments'].
		"&installment_amount_1st=".$data['installment_amount_1st'].
		"&installment_amount_2nd=".$data['installment_amount_2nd'].
		"&annual_amount=".$data['annual_amount']/*.
		"&payment_lent=".$array_payment_lent[$data['payment_lent']].
		"&house_type=".$array_house_type[$data['house_type']].
		"&living_grant=".$array_living_grant[$data['living_grant']].
		"&same_living_count=".$data['same_living_count'].
		"&annual_income=".$data['annual_income'].
		"&identification_type=".$array_identification_type[$data['identification_type']].
		"&identification_number=".$data['identification_number'].
		"&call_timezone=".$array_call_timezone[$data['call_timezone']]*/
		;
}

// 必須項目確認----------------------------------------------------------------------------
function validate(){
	$gMsg ="";

	if( empty($_POST['application_date']) )		$gMsg .= "<br />※申込日が未入力です。";

	if( empty($_POST['shop_id']) )				$gMsg .= "<br />※申込店舗を選択してください。";

	if( empty($_POST['staff_id']) )				$gMsg .= "<br />※担当者を選択してください。";

	if( empty($_POST['name']) )					$gMsg .= "<br />※名前を入力してください。。";
	elseif( !strpos($_POST['name'], "　"))		$gMsg .= "<br />※姓と名の間にスペースを入れてください。";

	if( empty($_POST['name_kana']) )			$gMsg .= "<br />※フリガナを入力してください。。";
	elseif( !strpos($_POST['name_kana'], "　"))	$gMsg .= "<br />※フリガナの姓と名の間にスペースを入れてください。";

	if(!$_POST['mail'])							$gMsg .= "<br />※メールアドレスを入力してください。";
	else{
		$rec = Check_Email2($_POST["mail"]);
		if($rec["flg"] == 1) 					$gMsg .=  "<br />※".$rec["error"];
	}

	if(!$_POST['tel'])							$gMsg .= "<br />※電話番号を入力してください。";

	if(!$_POST['birthday'] || $_POST['birthday'] == "0000-00-00" )
												$gMsg .= "<br />※生年月日を入力してください。";

	if( Check_Zip($_POST['zip']) )				$gMsg .= "<br />※". Check_Zip($_POST['zip']);

	if( empty($_POST['pref']) )					$gMsg .= "<br />※都道府県を選択してください。";

	if( empty($_POST['address']) )				$gMsg .= "<br />※住所を入力してください。";

	// if( empty($_POST['number_of_payments']) )	$gMsg .= "<br />※支払回数を選択してください。";

	if(!is_numeric($_POST['save_amount']))$gMsg .= "<br />※預貯金額を数字のみで入力してください。";
	elseif($_POST['save_amount']>9999)		$gMsg .= "<br />※預貯金額は正しくありません。";

	if(!is_numeric($_POST['side_income']))$gMsg .= "<br />※副収入の年額を数字のみで入力してください。";
	elseif($_POST['side_income']>9999)		$gMsg .= "<br />※副収入の年額は正しくありません。";


    if( $_POST['amount']>560000 )               $gMsg .= "<br />※申込金額は正しくありません。";

		if( empty($_POST['number_of_payments']) ) $gMsg .= "<br />※支払回数を選択してください。";
				elseif( $_POST['amount'] != 50000 && $_POST['number_of_payments'] == 19 ) $gMsg .= "<br />※19回は申込金額が5万円の時のみ選択できます。";
        else {
            if( $_POST['amount'] == 30000 && $_POST['number_of_payments'] > 6 ) $gMsg .= "<br />※6回までしか選択できません。";
            if( $_POST['amount'] == 40000 && $_POST['number_of_payments'] > 6 ) $gMsg .= "<br />※6回までしか選択できません。";
            if( $_POST['amount'] == 50000 && $_POST['number_of_payments'] > 19 ) $gMsg .= "<br />※19回までしか選択できません。";
            if( $_POST['amount'] == 60000 && $_POST['number_of_payments'] > 18 ) $gMsg .= "<br />※18回までしか選択できません。";
            if( $_POST['amount'] == 70000 && $_POST['number_of_payments'] > 24 ) $gMsg .= "<br />※24回までしか選択できません。";
            if( $_POST['amount'] == 80000 && $_POST['number_of_payments'] > 30 ) $gMsg .= "<br />※30回までしか選択できません。";
            if( $_POST['amount'] == 90000 && $_POST['number_of_payments'] > 36 ) $gMsg .= "<br />※36回までしか選択できません。";
            if( $_POST['amount'] == 100000 && $_POST['number_of_payments'] > 42 ) $gMsg .= "<br />※42回までしか選択できません。";
            if( $_POST['amount'] == 110000 && $_POST['number_of_payments'] > 48 ) $gMsg .= "<br />※48回までしか選択できません。";
            if( $_POST['amount'] == 120000 && $_POST['number_of_payments'] > 54 ) $gMsg .= "<br />※54回までしか選択できません。";
        }


	// if( empty($_POST['first_payment_year']) )	$gMsg .= "<br />※支払初月(年)を選択してください。";

	// if( empty($_POST['first_payment_month']) )	$gMsg .= "<br />※支払初月(月)を選択してください。";

	/*if( empty($_POST['transfer_status']) )		$gMsg .= "<br />※支払方法を選択してください。";

	if( empty($_POST['payment_lent']) )			$gMsg .= "<br />※家賃負担を選択してください。";

	if( empty($_POST['house_type']) )			$gMsg .= "<br />※お住まいを選択してください。";

	if( empty($_POST['living_grant']) )			$gMsg .= "<br />※生活費の援助を選択してください。";

	if( empty($_POST['same_living_count']) )	$gMsg .= "<br />※同一生計人数を選択してください。";

	if( empty($_POST['annual_income']) )		$gMsg .= "<br />※年収を入力してください。";
	elseif(!is_numeric($_POST['annual_income']))$gMsg .= "<br />※年収を数字のみで入力してください。";
	elseif($_POST['annual_income']>9999)		$gMsg .= "<br />※年収は正しくありません。";

	if( $_POST['identification_type'] <> 1){
		if(!empty($_POST['identification_number']) ) $gMsg .= "<br />※運転免許証以外の番号を入れてないでください。";
	}else{
		if(empty($_POST['identification_number']) )	$gMsg .= "<br />※運転免許証番号を入力してください。";

		elseif(!is_numeric($_POST['identification_number']))$gMsg .= "<br />※運転免許証番号を数字のみで入力してください。";

		elseif(strlen($_POST['identification_number']) <> 12)	$gMsg .= "<br />※運転免許証番号を12桁で入力してくだい。";

		elseif(substr($_POST['identification_number'],0,1)==0)	$gMsg .= "<br />※運転免許証番号が正しくありません。";
	}

	if( empty($_POST['call_timezone']) )		$gMsg  = "<br />※電話連絡可能時間帯を選択してください。";
	
	if( empty($_POST['verify_datetime_date1']) || empty($_POST['verify_datetime_time1']) )
												$gMsg .= "<br />※ベリファイ日時1を選択してください。";

	elseif( ($_POST['verify_datetime_date2'] == $_POST['verify_datetime_date1']) && ($_POST['verify_datetime_time2'] == $_POST['verify_datetime_time1'])
		 || ($_POST['verify_datetime_date3'] == $_POST['verify_datetime_date1']) && ($_POST['verify_datetime_time3'] == $_POST['verify_datetime_time1']) )
												$gMsg .= "<br />※ベリファイ日時2またベリファイ日時3ベリファイ日時1が同じです。";

	if( 	($_POST['verify_datetime_date2'] == $_POST['verify_datetime_date3']) && ($_POST['verify_datetime_time2'] == $_POST['verify_datetime_time3'])
		 && !empty($_POST['verify_datetime_time2'])  )
												$gMsg .= "<br />※ベリファイ日時2とベリファイ日時3が同じです。";
	
	if( empty($_POST['credit_app_agree']) )		$gMsg .= "<br />※「クレジットお申し込みの内容同意」にチェエク入れてください。";

	if( empty($_POST['privacy_agree']) )		$gMsg .= "<br />※「個人情報の取り扱いに関する同意」にチェエク入れてください。";*/

	if($authority_level && $contract['customer_id'] && $_REQUEST['contract_id']){
		$loan_reg_date = Get_Table_Col("loan_info","reg_date"," WHERE del_flg=0 AND customer_id = '".addslashes($contract['customer_id'])."' AND contract_id = '".addslashes($_REQUEST['contract_id'])."'");
		if(substr($loan_reg_date,0,7)<>date('Y-m-d')){
			$gMsg = "<br />※ローン申込の編集が当日のみです。";
		}

	}

	if($gMsg) $gMsg = "<font color='red' size='-1'>".$gMsg."</font>";

	return $gMsg;
}

