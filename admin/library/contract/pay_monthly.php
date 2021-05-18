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

$table = "sales";

$contract = Get_Table_Row("contract"," where id=".$_POST['contract_id']);
$customer = Get_Table_Row("customer"," where id=".$_POST['customer_id']);

// 登録済みのカード有効期限の期限チェック 20151110 shimada
$reg_target_ymd = yearFormat($customer['card_limit_year'],1)."/".$customer['card_limit_month']."/01";
$reg_target_ymd = date('Y/m/t', strtotime(date($reg_target_ymd))); // 指定日の末日
$reg_end_ymd    = date("Y/m/t"); // 今月末日

	// 	登録済みカードの形式チェック
	$card_error_msg .= "<br>";
	if($customer['card_limit_year'] <>"" && $customer['card_limit_month'] <>""){
		if(!is_numeric($customer['card_limit_year']) || !is_numeric($customer['card_limit_month'])){
		$card_error_msg .= "<span style='color:red;'>※　登録済みカード有効期限の形式を確認してください。</span><br>";
		$card_error_flg = true;
		} elseif(checkLimitDate($reg_target_ymd,$reg_end_ymd)==true) {
			// 登録済みカードの有効期限チェック
			$card_error_msg .= "<span style='color:red;'>※　登録済みのカード有効期限が切れています。</span><br>";
			$card_error_flg = true;
		}
	}

// 月額未払いチェック 20151117shimada------------------------------------------------------------
// コース情報
if($contract)$course = Get_Table_Row("course"," where id=".$contract['course_id']);

// 支払期間のベースとなる最小日と最大日を取得する
// 条件
//   最小日(MIN_DATE): 契約日以上の支払日  1回目の消化
//   最大日(MAX_DATE): 解約日以下の日にち / 解約日がない場合は現在の日付以下の日にち
$cSql1 = "SELECT s.id,s.customer_id,MIN(s.pay_date) AS MIN_DATE,CASE WHEN t.cancel_date <> \"0000-00-00\" THEN substring(t.cancel_date,1,10) ELSE substring(NOW(),1,10) END AS MAX_DATE FROM sales s,contract t ";
$cSql1 .=" WHERE s.contract_id = t.id AND s.del_flg=0 AND 1 <= s.r_times AND t.contract_date <=s.pay_date AND s.pay_date <= (CASE WHEN t.cancel_date<>\"0000-00-00\" THEN substring(t.cancel_date, 1, 10) ELSE substring(NOW(), 1, 10) END) ";
$cSql1 .=" AND s.customer_id=".$_POST['customer_id'] ." AND t.id=".$contract['id'];
$cSql1 .=" GROUP BY t.customer_id";
$cSql1_result = Get_Result_Sql_Row($cSql1);

// 支払済年月取得する 旧月額：最初の消化月～、新月額：施術開始年月～ 20170210 add by shimada
if(empty($cSql1_result) == false || ($course['type']==1 && $course['new_flg']==1)){

	// 支払済年月取得する
$cSql2 = "SELECT id,customer_id,(CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) AS YearMonth, option_month AS Month,option_year AS Year, (option_price + option_transfer + option_card) AS PAY_PRICE, (fixed_price - discount) AS COURSE_PRICE FROM " . $table ;
$cSql2 .=" WHERE del_flg=0 AND option_name=4 AND customer_id=".$_POST['customer_id'] ." AND contract_id=" .$contract['id'];
//$cSql2 .=" GROUP BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END)";
//$cSql2 .=" HAVING SUM(option_price + option_transfer + option_card) <>0";
$cSql2 .=" ORDER BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) ASC";
$cSql2_result = Get_Result_Sql_Array($cSql2);

// 最小日～最大日までの支払う期間の配列を作る
// 新月額
if($course['type']==1 && $course['new_flg']==1){
	// 施術開始年月を支払開始年月とする 2017/02/08 add by shimada
	$start_ym	 = date('Y/m', strtotime($contract['start_ym'].'01'));
	$start_ymd	 = date('Y-m-01', strtotime($contract['start_ym'].'01'));
	//　解約日がある場合は、解約月を支払終了年月とする
	if($contract['cancel_date']<>'0000-00-00'){
		// 本日+1ヵ月後の月 < 解約日の月　の場合は、支払終了日を 本日+1ヵ月後の月 までチェックする add by shimada
		// 　例）現在：2017/03+1ヵ月 < 解約：2017/05 のとき、2017/04月分の支払いまでチェックし、払っていなければ2017/03・04まで未払いエラーとする
		//　　　　不要な処理であれば下記、「既存の書き方」のコメントアウトを解除し、使ってください。
		if(date("Y-m-01",strtotime(date('Y-m-01') . "+1 month")) < date('Y-m-01', strtotime($contract['cancel_date']))){
			$end_ym 	 = date("Y/m",strtotime(date('Y-m-01') . "+1 month")); // 支払終了年月(支払予定月の表示用)
	} else {
			// 新月額で解約日があった場合、解約月に施術済みであれば、そのタームの過払いエラーを表示しない 2017/06/29 add by shimada
			// 現在のタームの年月配列を作成する
			$treatment_term_array = CurrentTerm($contract['cancel_date'],$contract['start_ym']);
			$treatment_term = implode(',',$treatment_term_array); 
			// 解約月に施術があるか判定する
			$treatment_data = Get_Table_Row("sales"," where contract_id='".$contract['id']."' AND type=2 AND 0 < r_times AND DATE_FORMAT(pay_date, '%Y%m') IN (".$treatment_term.")");
			if($treatment_data){
				$end_ym		 = date('Y/m', strtotime($contract['cancel_date']));   // 支払終了年月(支払予定月の表示用)
				$end_ymd     = date('Y-m-01', strtotime($contract['cancel_date']));   // 支払終了年月日(メッセージの表示用)
			} else {
				$end_ym		 = date('Y/m', strtotime($contract['cancel_date']));   // 支払終了年月(支払予定月の表示用)
				$end_ymd     = date('Y-m-01', strtotime($contract['cancel_date']));   // 支払終了年月日(メッセージの表示用)
			}
		}
	} else {
		// 月額支払のテストが終わったら、下記のコメントアウトを外して、テストを行う。（テストが競合しないように一時コメントアウトしています。）
		// #1201　予約詳細：新月額未払い過払い判定を翌々月まで有効にする
		// 1ヵ月先分の支払まで確認する 2017/03/15 add by shimada
		$end_ym	 = date("Y/m",strtotime(date('Y-m-01') . "+1 month"));
		$end_ymd = date("Y-m-01",strtotime(date('Y-m-01') . "+1 month"));
	}
// 旧月額
} else {
	// 最初の消化を支払開始年月とする
$start_ym	 = date('Y/m', strtotime($cSql1_result['MIN_DATE']));
 	$start_ymd	 = date('Y-m-01', strtotime($cSql1_result['MIN_DATE']));
 	// 支払終了月
$end_ym		 = date('Y/m', strtotime($cSql1_result['MAX_DATE']));
 	$end_ymd     = date('Y-m-01', strtotime($cSql1_result['MAX_DATE']));
}
$minus_month = "0";
$plus_month  = $contract['times']; // 何度無料体験があるか、回数を設定する(月額の回数)

// 前回の契約が月額だった方の対応(プラン変更者対応)　2017/03/30 add by shimada
if($course['type']==1 && $course['new_flg']==1 && $contract['old_contract_id']<>0){
	// 複数契約IDの最大値が現在の契約IDと同じだったら、無料月を支払い月としてカウントしない
	$cSql3  = "SELECT t.id,u.type FROM contract t ,course u " ;
	$cSql3 .= " WHERE t.course_id = u.id AND u.type =1 AND t.del_flg=0 AND t.id=".$contract['old_contract_id'];
	$cSql3_result = Get_Result_Sql_Row($cSql3);
	// 前回の契約が月額だった場合、無料月なしとする
	if($cSql3_result['id']<>""){
			$plus_month  = 0; // 先払いなし（course.timesを無料月とカウントしない）
		}
	}


// 支払わなければいけない年・月の配列
$checkYM 	 = yearMonthArray($start_ym,$end_ym,$plus_month,$minus_month);

	//月額休会期間の表示
$monthly_pause_list = Get_Table_Array("monthly_pause", "*", " WHERE del_flg=0 AND contract_id = '" . addslashes($contract['id']) . "' ");
if(count($monthly_pause_list) > 0) {
	$monthly_pause_msg = "<span style='color:red;font-size:15px;'>休会期間 ";
	foreach($monthly_pause_list as $monthly_pause) {
			$pause_start_date = date('Y/m', strtotime($monthly_pause['pause_start_date']));
			isset($monthly_pause['pause_end_date']) ? $pause_end_date = date('Y/m', strtotime($monthly_pause['pause_end_date'])) : $pause_end_date = "";
			$monthly_pause_msg   .= $pause_start_date . "～" . $pause_end_date. "、";
	}
		$monthly_pause_msg = mb_substr($monthly_pause_msg, 0, -1);
		$monthly_pause_msg .= "</span>";
}

// 支払った月があるか確認する
$month_flg = false; //複数支払月なし
$ym_flg    = false; //単月支払月なし
if($cSql2_result <>""){
	// 過払い・その他ミス
	foreach ($cSql2_result as $value) {
		// 複数月分の支払 YYYY/MMの形でばらして再度配列に入れる
		if (is_numeric($value['Month']) ==false) {
			$month_flg = true;
			$month = explode(',',$value['Month']);
			$month_count = count($month); // 支払済みの複数月の数
			foreach ($month as $value2) {
					$month_ym = $value['Year'].'/'.monthFormat($value2,0);
				// 契約中で未来日の支払はチェックしない(未来月を過払いエラーにしない)
				// 解約済みで未来日の支払いはチェックする(未来月を過払いエラーにする) 2017/03/30 add by shimada
				if($month_ym <= $end_ym || $contract['cancel_date']<>'0000-00-00'){
					// 支払い済み金額+コース金額で0円を超えた場合のみ支払い済み月として扱う 2017/02/22 add by shimada
					if(0 <(($value['PAY_PRICE']/$month_count)+$value['COURSE_PRICE'])){
						$month_pay_data[] = array('ym'=>$month_ym,'ym_price'=>$value['PAY_PRICE']/$month_count,'course_price'=>$value['COURSE_PRICE']); //支払データ
						$months[]   = $month_ym;
					} else {
						$months[]   = "";
					}
					// 重複チェック用の配列を別途作成する 2017/03/21 add by shimada
					$dup_month_pay_data[] = array('ym'=>$month_ym,'ym_price'=>$value['PAY_PRICE']/$month_count,'course_price'=>$value['COURSE_PRICE']); //支払データ
					$dup_months[]   = $month_ym;
				} else {
					$months[]   = "";
					$dup_months[]   = "";
				}
			}
		} else {
			// 1ヶ月分の支払 YYYY/MM
			$ym_flg = true;
			// 未来日の支払はチェックしない(未来月を過払いエラーにしない)
			// 解約済みで未来日の支払いはチェックする(未来月を過払いエラーにする) 2017/03/30 add by shimada
			if($value['YearMonth'] <= $end_ym || $contract['cancel_date']<>'0000-00-00'){
				// 支払い済み金額+コース金額で0円を超えた場合のみ支払い済み月として扱う 2017/02/22 add by shimada
				if(0 <($value['PAY_PRICE']+$value['COURSE_PRICE'])){
					$ym_pay_data[] = array('ym'=>$value['YearMonth'],'ym_price'=>$value['PAY_PRICE'],'course_price'=>$value['COURSE_PRICE']); //支払データ
					$yms[] =$value['YearMonth'];
				} else {
					$yms[] ="";
				}
				// 重複チェック用の配列を別途作成する 2017/03/21 add by shimada
				$dup_ym_pay_data[] = array('ym'=>$value['YearMonth'],'ym_price'=>$value['PAY_PRICE'],'course_price'=>$value['COURSE_PRICE']); //支払データ
				$dup_yms[] =$value['YearMonth'];
			} else {
				$yms[] ="";
				$dup_yms[] ="";
			}
		}
	}
	// 1ヶ月分・複数月分の支払年月のデータをマージ
	if($month_flg ===true && $ym_flg ===true){
		$pay_ym   = array_merge($months,$yms);                 				// 支払月(未払い月・過払い月チェック用)
		$pay_data = array_merge($month_pay_data,$ym_pay_data); 				// 支払情報(未払い月・過払い月チェック用)
		$dup_pay_ym   = array_merge($dup_months,$dup_yms);                  // 支払月(重複月チェック用) 2017/03/21 add by shimada
		$dup_pay_data = array_merge($dup_month_pay_data,$dup_ym_pay_data);  // 支払情報(重複月チェック用) 2017/03/21 add by shimada
	} elseif($month_flg ===true && $ym_flg ===false) {
		$pay_ym   = $months;												// 支払月(未払い月・過払い月チェック用)
		$pay_data = $month_pay_data;										// 支払情報(未払い月・過払い月チェック用)
		$dup_pay_ym   = $dup_months;										// 支払月(重複月チェック用) 2017/03/21 add by shimada
		$dup_pay_data = $dup_month_pay_data;								// 支払情報(重複月チェック用) 2017/03/21 add by shimada
	} elseif($month_flg ===false && $ym_flg ===true) {
		$pay_ym = $yms;														// 支払月(未払い月・過払い月チェック用)
		$pay_data = $ym_pay_data;											// 支払情報(未払い月・過払い月チェック用)
		$dup_pay_ym   = $dup_yms;											// 支払月(重複月チェック用) 2017/03/21 add by shimada
		$dup_pay_data = $dup_ym_pay_data;									// 支払情報(重複月チェック用) 2017/03/21 add by shimada
	}
	// 未払い・過払い可能性あり月から、重複を取り除く（重複チェックは$dup_pay_ymを使用してチェックする）
	$pay_ym2 = array_unique($pay_ym);
	
	// 支払重複チェック※キーとしている日付 YYYY/MMに重複があれば返す
	//※キーとしている日付 YYYY/MMに重複があれば返す
	if(detectDuplication($dup_pay_ym) <> ""){
		$dup_array = detectDuplication($dup_pay_ym);
		foreach ($dup_array as $key =>$value) {
			$dup_key[] = $key;
		}
	}

	// 未払い・過払い（ミス）の時の表示用
	foreach ($dup_key as $d) {
		foreach ($dup_pay_data as $key => $value) {
			// 月ごとをキーにして算出する
			if($value['ym'] == $d){
				// 重複支払あり： 支払金額合計を入れる
				$dup_key3[$value['ym']] = array('ym_price'=>$dup_key2[$value['ym']] += $value['ym_price'],'course_price'=>$value['course_price']);
			} 
		}
	}

		// 重複エラーの中で過払い/未払いをチェック
	// 条件分岐を作成しなおしました 2017/04/03 add by shimada
	foreach ($dup_key3 as $key3 => $value3) {
		// 支払うべき月に含まれている
			if(array_search($key3,$checkYM) != false ){
			// 支払金額合計が0円以下
			if($value3['ym_price']<= 0){
				// 未払い(重複エラー/未払い)
				$dup_key5[] = $key3;
			// 支払金額合計がコース金額(sales.fixrd_price-sales.discount)よりも大きい
			} else if($value3['course_price']<$value3['ym_price']) {
			// 過払い(重複エラー/過払い)
				$dup_key4[] = $key3;
			}
		// 支払うべき月に含まれていない
		} else {
			// 支払金額合計が0円超える
			if($value3['ym_price']>0){
				// 過払い(重複エラー/過払い)
				$dup_key4[] = $key3;
			}
		}
	}

	// 支払い月が含まれるかチェックし、未払い・過払いを判定する
	$pay_not_ym     = array_unique(array_diff($checkYM, $pay_ym2)); // 未払いの可能性あり
	$pay_error_ym   = array_unique(array_diff($pay_ym2, $checkYM)); // 過払いかミスの可能性あり
	// 重複データがあった場合、重複月未払い/過払を配列から取り除く
	if( 0 < count($dup_key)){
		$pay_not_ym     = array_diff($pay_not_ym,$dup_key); 			// 重複月未払いを取り除く
		$pay_error_ym   = array_diff($pay_error_ym,$dup_key);		    // 重複月過払いを取り除く
	}
	// 新月額で解約日があった場合、解約月に施術済みであれば、そのタームの過払いエラーを表示しない 2017/06/29 add by shimada
	if($contract['cancel_date']<>"" && $course['new_flg']==1){
		// 現在のタームの年月配列を作成する
		$treatment_term_array = CurrentTerm($contract['cancel_date'],$contract['start_ym']);
		$treatment_term = implode(',',$treatment_term_array); 
		// 解約月に施術があるか判定する
		$treatment_data = Get_Table_Row("sales"," where contract_id='".$contract['id']."' AND type=2 AND 0 < r_times AND DATE_FORMAT(pay_date, '%Y%m') IN (".$treatment_term.")");
		if($treatment_data){
			// 施術済み月の過払いを除外する
			foreach ($treatment_term_array as $value) {
				$diff_month ="";
				$diff_month = date('Y/m',strtotime($value.'01'));
				// 過払いを取り除く
				$pay_error_ym   = array_diff($pay_error_ym,array($diff_month));	
			}
		}
	}

	// データの重複を取り除き、配列別に年月を格納数する
	$pay_not_ym   = implode(",", array_filter($pay_not_ym));   // 未払いの可能性あり
	$pay_error_ym = implode(",", array_filter($pay_error_ym)); // 過払いの可能性あり
	$dup_key_ym     = implode(",", $dup_key4); // 支払重複(過払い)の可能性あり
	$dup_key_ym2    = implode(",", $dup_key5); // 支払重複(未払い)の可能性あり
} else {
	// 支払がない場合、支払年月すべてを表示
	$pay_not_all_ym   = implode(",", $checkYM);
}


// 表示用にデータを整形する
// エラーメッセージ表示用
	$pay_min_date   = $start_ymd; // 138行目で設定した支払予定最小日を設定する
	$pay_max_date   = $end_ymd;   // 157行目で設定した支払予定最大日を設定する
	$pay_min_month  = date("Y/m", strtotime($pay_min_date." + ".$plus_month." month"));
	$pay_max_month  = date("Y/m", strtotime($pay_max_date." - ".$minus_month." month"));
					  date("Y-m-d",strtotime($target_day . "+1 month"));
	// 施術開始月が当月の2ヶ月以上先の場合に支払予定の最大月を非表示にする
	if($pay_max_month < $pay_min_month && $contract['cancel_date']=="0000-00-00"){
	 	$pay_max_month ="";
	}
$pay_monthly    = "<span style='color:red;font-size:15px;'>※支払予定月※ ".$pay_min_month."～".$pay_max_month."</span>";

mb_language('Japanese');
mb_internal_encoding('UTF-8');
$pay_error_msgs ="";
if($pay_not_all_ym <>""){
	$pay_error_msgs .= $pay_not_all_ym."(年月)に未払いの可能性があります。<br>";
}
if($pay_not_ym <>""){
	$pay_error_msgs .= $pay_not_ym."(年月)に未払いの可能性があります。<br>";
}
if($pay_error_ym <>""){
	$pay_error_msgs .= $pay_error_ym."(年月)に過払い、もしくはエラーの可能性があります。<br>";
}
if($dup_key_ym <>""){
	$pay_error_msg .= $dup_key_ym."(年月)に重複/過払いの可能性があります。<br>";
}
if($dup_key_ym2 <>""){
	$pay_error_msg .= $dup_key_ym2."(年月)に重複/未払いの可能性があります。<br>";
}

// width=57ごとに改行してエラー表示する
if($pay_error_msgs<>""){
	foreach(explode("\n", $pay_error_msgs) as $str) {
	  $str_width = 0;
	  do {
	    $str_width = mb_strwidth($str,"UTF-8");
	    $trim_str = mb_strimwidth($str, $start, 57, "\n", 'UTF-8');
	    $str = mb_substr($str, mb_strlen($trim_str,"UTF-8") - 1);
	    $pay_error_msg .= $trim_str;
	  } while($str_width > 57);
	}
}
$pay_error_msg = "<span style='color:red;'>".$pay_error_msg."</span>";


}

// データの取得------------------------------------------------------------------------

// 支払年月(降順)にソート
$dSql = $GLOBALS['mysqldb']->query("SELECT * FROM " . $table . " WHERE del_flg=0 and option_name=4 and customer_id=".$_POST['customer_id']." AND contract_id=". $contract['id']." ORDER BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) DESC,pay_date DESC") or die('query error'.$GLOBALS['mysqldb']->error);
if ($dSql) {
	while ( $result = $dSql->fetch_assoc() ) {
		$list[$result['id']]['pay_month'] = date("n", strtotime($result['pay_date']));
		$list[$result['id']]['pay_amount'] = $result['option_price']+$result['option_transfer']+$result['option_card'];

		if($result['option_card'] && $result['option_card']>$result['option_price']) $list[$result['id']]['pay_type'] = 2;
		elseif($result['option_transfer'] && $result['option_transfer']>$result['option_price']) $list[$result['id']]['pay_type'] = 3;
		elseif($result['option_price']) $list[$result['id']]['pay_type'] = 1;

		$list[$result['id']]['pay_date'] = $result['pay_date'];
		$list[$result['id']]['option_date'] = $result['option_date'];
		$list[$result['id']]['option_year'] = $result['option_year'];
		$list[$result['id']]['option_month'] = $result['option_month'];
		$list[$result['id']]['reservation_id'] = $result['reservation_id'];

	}
}

//店舗リスト---------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//courseリスト------------------------------------------------------------------------
$course_list  = getDatalist("course");

// リトライフラグが立っている場合情報を非表示 add by ka 20170824
if($contract['payinfo_del_flg']){
	unset($contract);
	unset($customer);
}

?>
