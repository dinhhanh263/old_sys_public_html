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

$table = "sales";

// 引き落とし情報登録処理------------------------------------------------------------------------

if( $_POST['contract_id'] && $_POST['mode'] == "reg" && $_POST['id'] >= 1 ){
	$sql = "UPDATE contract SET pay_shop='".$_POST['pay_shop']."' ,pay_reg_date='".$_POST['pay_reg_date']."' , pay_type='".$_POST['pay_type']."' , card_type='".$_POST['card_type']."'";
	$sql .= " WHERE id = '".addslashes($_POST['contract_id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
}

// カード情報登録処理
if( $_POST['customer_id'] && $_POST['mode'] == "reg" && $_POST['id'] >= 1 ){
	
	// カードの有効期限 20151110 shimada
    // $card_type_error_flg = false;
    // if($_POST['pay_type']==""){
   	// 	$card_type_error_flg = true;
    // }

	// カードの有効期限 20151110 shimada
  if($_POST['pay_type']==2 ){
	// カードのエラーチェック
	$card_error_msg = "<br>";
	$card_error_flg = false;

	// 入力があった場合のみ、カード入力エラーチェックを行う 20151117 shimada
	if($_POST['card_limit_year'] <>"" && $_POST['card_limit_month'] <>""){

		if(!is_numeric($_POST['card_limit_month']) || 12 < $_POST['card_limit_month'] || $_POST['card_limit_month'] <= 0){
			$card_error_msg .= "<span style='color:red;'>※　カード有効期限(月)は01～12の数字で入力してください。</span><br>";
			$card_error_flg = true;
		}
		if(!is_numeric($_POST['card_limit_year']) || strlen($_POST['card_limit_year'])<>2){
			$card_error_msg .= "<span style='color:red;'>※　カード有効期限(年)は下2桁の数字で入力してください。</span><br>";
			$card_error_flg = true;
		}
		// 登録時の有効期限のチェック
		// ※コメントアウトすると期限切れでも登録できるようになります。
		if($card_error_flg ==false && is_numeric($_POST['card_limit_year']) && is_numeric($_POST['card_limit_month'])){
			$_POST['card_limit_year']  = Che_Num3($_POST['card_limit_year']);       // 年
			$_POST['card_limit_month'] = monthFormat($_POST['card_limit_month'],0); // 0ありの月を返す

			$target_ymd = yearFormat($_POST['card_limit_year'],1)."/".$_POST['card_limit_month']."/01";
			$target_ymd = date('Y/m/t', strtotime(date($target_ymd))); // 指定日の末日
			$end_ymd    = date("Y/m/t"); // 今月末日
			
			if(checkLimitDate($target_ymd,$end_ymd)==true){
				$card_error_msg .= "<span style='color:red;'>※　カード有効期限が切れています。</span><br>";
				$card_error_flg = true;
			}		
		}
  }
		// カード情報更新
		if(($card_error_flg ===false && $_POST['card_limit_year']<>"" && $_POST['card_limit_month']<>"") || ($_POST['card_limit_year']=="" && $_POST['card_limit_month']=="")){
			$sql = "UPDATE customer SET card_name_kana='".$_POST['card_name_kana']."' , card_name='".$_POST['card_name']."' , card_no='".$_POST['card_no']."' ,card_limit_month='".$_POST['card_limit_month']."' ,card_limit_year='".$_POST['card_limit_year']."'";
			$sql .= " WHERE id = '".addslashes($_POST['customer_id'])."'";
			$dRes = $GLOBALS['mysqldb']->query($sql);
		} else if(($_POST['card_limit_year']=="" && $_POST['card_limit_month']<>"") || ($_POST['card_limit_year']<>"" && $_POST['card_limit_month']=="")) {
			$card_error_msg .= "<span style='color:red;'>※　カード有効期限は年月両方入力してください。</span><br>";
		}
	}
	// 支払方法選択エラー
	//if($card_type_error_flg === true){
   	//	$card_type_error_msg = "<br><span style='color:red;'>※　支払方法を選択してください。</span><br>";
    //}
}

$customer = Get_Table_Row("customer"," where id=".$_POST['customer_id']);
$contract = Get_Table_Row("contract"," where id=".$_POST['contract_id']);
$reservation = Get_Table_Row("reservation"," where contract_id=".$contract['id']);

// 登録済みのカード有効期限の期限チェック 20151110 shimada
$reg_target_ymd = yearFormat($customer['card_limit_year'],1)."/".$customer['card_limit_month']."/01";
$reg_target_ymd = date('Y/m/t', strtotime(date($reg_target_ymd))); // 指定日の末日
$reg_end_ymd    = date("Y/m/t"); // 今月末日

// 入力中のカード年と月がなければ改行をいれる
if($_POST['card_limit_month'] =="" || $_POST['card_limit_year'] ==""){
	$card_error_msg .= "<br>";
	// 	登録済みカードの形式チェック
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
}


// 月額未払いチェック 20151117shimada------------------------------------------------------------

// 支払期間のベースとなる最小日と最大日を取得する
// 条件
//   最小日(MIN_DATE): 契約日以上の支払日  1回目の消化
//   最大日(MAX_DATE): 解約日以下の日にち / 解約日がない場合は現在の日付以下の日にち
$cSql1 = "SELECT s.id,s.customer_id,MIN(s.pay_date) AS MIN_DATE,CASE WHEN t.cancel_date <> \"0000-00-00\" THEN substring(t.cancel_date,1,10) ELSE substring(NOW(),1,10) END AS MAX_DATE FROM sales s,contract t ";
$cSql1 .=" WHERE s.contract_id = t.id AND s.del_flg=0 AND 1 <= s.r_times AND t.contract_date <=s.pay_date AND s.pay_date <= (CASE WHEN t.cancel_date<>\"0000-00-00\" THEN substring(t.cancel_date, 1, 10) ELSE substring(NOW(), 1, 10) END) ";
$cSql1 .=" AND s.customer_id='".$_POST['customer_id'] ."' AND t.id='".$reservation['contract_id'] ."'";
$cSql1 .=" GROUP BY t.customer_id";
$cSql1_result = Get_Result_Sql_Row($cSql1);

// 支払済年月取得する
if(empty($cSql1_result) == false){

	// 支払済年月取得する
$cSql2 = "SELECT id,customer_id,(CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) AS YearMonth, option_month AS Month,option_year AS Year, (option_price + option_transfer + option_card) AS PAY_PRICE, (fixed_price - discount) AS COURSE_PRICE FROM " . $table ;
$cSql2 .=" WHERE del_flg=0 AND option_name=4 AND customer_id=".$_POST['customer_id'] ." AND contract_id=" .$reservation['contract_id'];
//$cSql2 .=" GROUP BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END)";
//$cSql2 .=" HAVING SUM(option_price + option_transfer + option_card) <>0";
$cSql2 .=" ORDER BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) ASC";
$cSql2_result = Get_Result_Sql_Array($cSql2);

// 最小日から3ヶ月目の年月～最大日までの配列を作る
$start_ym	 = date('Y/m', strtotime($cSql1_result['MIN_DATE']));
$end_ym		 = date('Y/m', strtotime($cSql1_result['MAX_DATE']));
	$minus_month = "0";
// 20151118 shimada 下記の機能廃止、当月まで支払チェックするように変更。
	if($contract['dis_type'] == "1"){
		$plus_month  = $contract['times'] +1;
	} else {
		$plus_month  = $contract['times']; // 何度無料体験があるか、回数を設定する(月額の回数)
	}

// 	$minus_month = "1";
// } else {
// 	$minus_month = "0";
// }
// } else {
// 	$minus_month = "0";
// }

// 過去に月額の契約IDがあるかチェックする
// 月額IDの最大値が現在の予約(契約ID)と同じだったら、無料月をカウントしない
$cSql3  = "SELECT MIN(t.id) AS MIN_T_ID,MAX(t.id) AS MAX_T_ID, t.course_id,u.type,t.status,t.contract_date FROM contract t ,course u " ;
$cSql3 .= " WHERE t.course_id = u.id AND u.type =1 AND t.customer_id=".$_POST['customer_id'];
$cSql3_result = Get_Result_Sql_Row($cSql3);
// 月額契約が複数あったとき、二度目以上となる
if($cSql3_result['MIN_T_ID'] <> $cSql3_result['MAX_T_ID']){
	if($reservation['contract_id'] == $cSql3_result['MAX_T_ID']){
		$plus_month  = 0; // 何度無料体験なし
	}	
}

// 支払わなければいけない年・月の配列
$checkYM 	 = yearMonthArray($start_ym,$end_ym,$plus_month,$minus_month);

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
				$month_pay_data[] = array('ym'=>$month_ym,'ym_price'=>$value['PAY_PRICE']/$month_count,'course_price'=>$value['COURSE_PRICE']); //支払データ
				// 未来日の支払はチェックしない(未来月を過払いエラーにしない)
				if($month_ym <= date("Y/m")){
					$months[]   = $month_ym;
				} else {
					$months[]   = "";
				}
			}
		} else {
			// 1ヶ月分の支払 YYYY/MM
			$ym_flg = true;
			$ym_pay_data[] = array('ym'=>$value['YearMonth'],'ym_price'=>$value['PAY_PRICE'],'course_price'=>$value['COURSE_PRICE']); //支払データ
			// 未来日の支払はチェックしない(未来月を過払いエラーにしない)
			if($value['YearMonth'] <= date("Y/m")){
			$yms[] =$value['YearMonth'];	
			} else {
				$yms[] ="";
			}			
		}
	}
	// 1ヶ月分・複数月分の支払年月のデータをマージ
	if($month_flg ===true && $ym_flg ===true){
		$pay_ym   = array_merge($months,$yms);                  // 支払月
		$pay_data = array_merge($month_pay_data,$ym_pay_data);  // 支払情報
	} elseif($month_flg ===true && $ym_flg ===false) {
		$pay_ym = $months;
		$pay_data = $month_pay_data;
	} elseif($month_flg ===false && $ym_flg ===true) {
		$pay_ym = $yms;
		$pay_data = $ym_pay_data;
	}
	// 未払い・過払い可能性あり月から、重複を取り除く（重複は$pay_ymを使用してチェックする）
	$pay_ym2 = array_unique($pay_ym);
	
	// 支払重複チェック※キーとしている日付 YYYY/MMに重複があれば返す
	//※キーとしている日付 YYYY/MMに重複があれば返す
	if(detectDuplication($pay_ym) <> ""){
		$dup_array = detectDuplication($pay_ym);
		foreach ($dup_array as $key =>$value) {
			$dup_key[] = $key;
		}
	}

	// 未払い・過払い（ミス）の時の表示用
	foreach ($dup_key as $d) {
		foreach ($pay_data as $key => $value) {
			// 月ごとをキーにして算出する
			if($value['ym'] == $d){
				// 重複支払あり： 支払金額合計を入れる
				$dup_key3[$value['ym']] = array('ym_price'=>$dup_key2[$value['ym']] += $value['ym_price'],'course_price'=>$value['course_price']);
			} 
		}
	}

	// 重複月の支払金額合計が0円以上のとき、重複エラーの月とする
		// 重複エラーの中で過払い/未払いをチェック
	foreach ($dup_key3 as $key3 => $value3) {
		if($value3['course_price'] < $value3['ym_price']){
			// 重複エラー 過払い
			$dup_key4[] = $key3;
		} elseif($value3['ym_price']-$value3['course_price'] = 0){
			// 未払い
		} else {
			// 重複エラー 未払い
			$dup_key5[] = $key3;
		}
	}

	$pay_not_ym     = array_unique(array_diff($checkYM, $pay_ym2)); // 未払いの可能性あり
	$pay_error_ym   = array_unique(array_diff($pay_ym2, $checkYM)); // 過払いかミスの可能性あり
	// 重複データがあった場合、重複月未払い/過払を配列から取り除く
	if( 0 < count($dup_key)){
		$pay_not_ym     = array_diff($pay_not_ym,$dup_key); 			// 重複月未払いを取り除く
		$pay_error_ym   = array_diff($pay_error_ym,$dup_key);		    // 重複月過払いを取り除く
	}


	$pay_not_ym   = implode(",", array_filter($pay_not_ym));
	$pay_error_ym = implode(",", array_filter($pay_error_ym));
	$dup_key_ym     = implode(",", $dup_key4); // 支払重複(過払い)の可能性あり
	$dup_key_ym2    = implode(",", $dup_key5); // 支払重複(未払い)の可能性あり
} else {
	// 支払がない場合、支払年月すべてを表示
	$pay_not_all_ym   = implode(",", $checkYM);
}


// 表示用にデータを整形する

// エラーメッセージ表示用
	$pay_min_date   = date("Y-m-01", strtotime($cSql1_result['MIN_DATE']));
	$pay_max_date   = date("Y-m-01", strtotime($cSql1_result['MAX_DATE']));
	$pay_min_month  = date("Y/m", strtotime($pay_min_date." + ".$plus_month." month"));
	$pay_max_month  = date("Y/m", strtotime($pay_max_date." - ".$minus_month." month"));
					  date("Y-m-d",strtotime($target_day . "+1 month"));
	// 支払開始予定月が、支払終了予定月を超えていたら注意文言を表示させる
	// ※支払予定月より前の月に解約しているため支払義務は発生しない
	if($pay_max_month < $pay_min_month && $contract['cancel_date']<>"0000-00-00"){
		$pay_max_month ="<br><span style='color:red;font-size:11px;'>".$pay_max_month."に解約済です。支払予定月はありません。";
	} else if($pay_max_month < $pay_min_month && $contract['cancel_date']=="0000-00-00"){
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
$dSql = $GLOBALS['mysqldb']->query("SELECT * FROM " . $table . " WHERE del_flg=0 and option_name=4 and customer_id=".$_POST['customer_id']." AND contract_id=". $reservation['contract_id']." ORDER BY (CASE WHEN 1 <=option_month AND option_month <=9 THEN CONCAT(option_year,\"/0\",option_month) ELSE  CONCAT(option_year,\"/\",option_month) END) DESC,pay_date DESC");

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

	}
}

//店舗リスト---------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();


//courseリスト------------------------------------------------------------------------
$course_list  = getDatalistMens("course");

?>
