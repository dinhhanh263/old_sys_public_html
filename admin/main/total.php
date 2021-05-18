<?php 
include_once("../library/sales/index.php");
if ( $dRtn3 ) {
	$i = 1;
	$cnt_monthly = 0;
	$cnt_pack = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//役務消化除外
		if($data['r_times'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card'] ) continue; 	

		$loan_status = Get_Table_Col("contract","loan_status"," where del_flg=0 and id=".$data['contract_id']);


		if( $data['type']==9) $data['balance']=0;


		if($data['type']==1 || $data['type']==6)$total_fixed_price += $data['fixed_price']; 						// コース金額,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_discount += $data['discount']; 								// 値引き合計,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_price += $data['fixed_price'] - $data['discount']; 			//請求金額（商品金額）
		// 残金支払合計
		if($data['type']<>1 && $data['type']<>6 && $data['type']<>10){
			if( $data['payment_cash']>0)$total_payment += $data['payment_cash'] ; 	
			if( $data['payment_card']>0)$total_payment += $data['payment_card'] ; 	
			if( $data['payment_transfer']>0)$total_payment += $data['payment_transfer'] ; 	
			if( $loan_status==1 && $data['payment_loan']>0)$total_payment += $data['payment_loan'] ; 
		}											

		$total_option_price += $data['option_price'] + $data['option_transfer'] + $data['option_card']; 			// オプション金額合計
		//$total_sales += $data['payment'] + $data['option_price']+ $data['payment_coupon']; 						// 入金金額（売上合計）
		$total_sales += $data['payment_cash'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon'] + $data['option_price'] + $data['option_transfer'] + $data['option_card'];

		//if($data['type']==1){
			if($pre_no == $data['no']) $total_balance -= $pre_balance; 	//最新の売掛だけを計上処理
			$total_balance += $data['balance']; // 売掛金合計
		//}

		if($data['type']==1){
			if($course_type[$data['course_id']]) $cnt_monthly++; 		// 月額件数
			else $cnt_pack++; // パック件数
		}

		$total_cash 	+= $data['payment_cash'] + $data['option_price']; 	// 現金売上合計
		$total_card 	+= $data['payment_card']  + $data['option_card']; 							// カード売上合計
		$total_transfer += $data['payment_transfer'] + $data['option_transfer']  ; 						// 銀行振込合計
		$total_loan 	+= $data['payment_loan'] ; 							// ローン売上合計
		$total_coupon 	+= $data['payment_coupon'] ; 						// クーポン売上合計

		//月額退会合計
		if($data['type']==5 && $course_type[$data['course_id']] ) {
			$total[20] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}else{
			$total[$data['type']] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}

		if($data['type']==4) $cnt4++;
		if($data['type']==5 && !$course_type[$data['course_id']] ) $cnt5++; //中途解約件数
		if($data['type']==5 && $course_type[$data['course_id']] )  $cnt20++; //月額退会件数
		if($data['type']==6) $cnt6++;
		if($data['type']==7) $cnt7++;
		if($data['type']==12) $cnt12++;

		$pre_no = $data['no'];
		$pre_balance = $data['balance'];
		if($data['type']==1) $isexited_contract = true;

		//最新売掛金を格納
		$balance[$data['customer_id']] = $data['balance'];

		$i++;
	}
		
		$total_balance = array_sum($balance);
		$total_balance = $isexited_contract ? $total_balance : 0; 			// 契約データがなければ0
		$total_without_balance = $total_sales; 								// 売掛含まない総合計


		//来店件数=未契約数+契約数（月額件数+パック件数）
		$cnt_came = $dGet_Cnt4 + $cnt_monthly + $cnt_pack ;
		//カウンセリング予約件数=来店件数+来店なし件数
		$cnt_total = $cnt_came + $dGet_Cnt5 ;
		//来店率=来店件数/カウンセリング予約件数*100%
		if($cnt_total) $percent_came = round($cnt_came/$cnt_total*100)."%";
		//成約率=成約件数/来店件数*100%
		if($cnt_came) $percent_contract = round(($cnt_monthly + $cnt_pack)/$cnt_came*100)."%";

}
echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:18px;padding-top:10px;">売掛含まない総合計: '.number_format($total_without_balance).' 円</div>';
?>

				