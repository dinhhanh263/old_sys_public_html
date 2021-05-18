<?php 
include_once("../library/reservation/cc_request.php"); 
$gAccountType = array(0=>'-',1=>'普通',2=>'当座',3=>'貯蓄');
//csv export----------------------------------------------------------------------

$filename = "cc_request.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3 ) {
	echo mb_convert_encoding("依頼事項,店舗,会員番号,名前,フリガナ,生年月日,電話番号,契約状況,契約日,コース名,頭金,ローン金額,解約手数料,ローン会社,銀行名,支店名,口座種別,口座番号,口座名義,依頼日,返金日・入金日,ステータス\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		$gRequestStatus = ($data['type']==2) ? $gShopRequest : $gCCRequest;

		// 消化単価
		$per_price = $data['times'] ? round(($data['fixed_price']-$data['discount'])/$data['times']) : 0;
		// 消化金額
		$usered_price = $per_price * $data['r_times'];
		// 手数料：返金の10％、最大2万円,月額が手数料なし
		if($data['times']<6) $charge = 0;
		elseif($data['sales_id']) {
			$charge = Get_Table_Col("sales","charge"," WHERE del_flg=0 AND type=5 AND contract_id = '".addslashes($data['contract_id'])."' ORDER BY id DESC LIMIT 1");
		}else{
			// 値引き後基準
			$charge = round(($data['fixed_price'] - $data['discount'] - $usered_price)*0.1);
			if($charge > 20000) $charge = 20000;
		}

		// 複数ローン会社表示処理
		$loan_company_name = $loan_company_list[$data['loan_company_id']];
		if($data['loan_company_id'] && $data['old_contract_id']){
			$old_loan_company_id = Get_Table_Col("contract","loan_company_id"," WHERE del_flg=0 AND loan_company_id<>0 AND id =".$data['old_contract_id']);
			if( $old_loan_company_id && $old_loan_company_id<>$data['loan_company_id'] ){
				$loan_company_name = $loan_company_name.'、'.$loan_company_list[$old_loan_company_id];
			}
		}
		echo mb_convert_encoding($gRequestStatus[$data['cc_request']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['no'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'] ? $data['name_kana'] : ($data['name'] ? $data['name'] : '無名'),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(($data['birthday']<>'0000-00-00' ? $data['birthday'] : ""),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['tel'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gContractStatus[$data['status']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['contract_date'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(($data['payment_loan'] ? ($data['fixed_price']-$data['discount']-$data['payment_loan']-$data['balance']) : '') ,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['payment_loan'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($charge,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($loan_company_name,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['bank_name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['bank_branch'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gAccountType[$data['bank_account_type']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['bank_account_no'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['bank_account_name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(substr($data['reg_date'],0,10),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(($data['transfer_date']<>'0000-00-00' ? $data['transfer_date'] : ""),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gLoanStatus[$data['loan_status']],"SJIS-win", "UTF-8")  . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
