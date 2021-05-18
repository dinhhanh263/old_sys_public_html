<?php include_once("../library/service/loan_application2.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- ブラウザの「戻る」ボタンを禁止-->
	<script type="text/javascript">
		function redirect(url)
		{
			var Backlen=history.length;
			history.go(-Backlen);
			window.location.replace(url);
		}
	</script>
</form>
<script type="text/javascript">
function conf_loan_application2() {
	if ( confirm(  "この内容でローン申込をして宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}
</script>
<script type="text/javascript">
$(function() {

  // 祝日を配列で確保 http://calendar.infocharge.net/cal/2019/
  var holidays =['2017-11-23', '2017-12-29', '2017-12-30', '2017-12-31',
  				 '2018-01-01', '2018-01-02', '2018-01-03', '2018-01-04', '2018-01-08', '2018-02-12', '2018-03-21', '2018-04-30',
  				 '2018-05-03', '2018-05-04', '2018-07-16', '2018-09-17', '2018-09-24', '2018-10-08', '2018-11-23', '2018-12-24',
  				 '2018-12-30', '2018-12-31',
  				 '2019-01-01', '2019-01-02', '2019-01-03', '2019-01-14', '2019-02-11', '2019-03-21', '2019-04-29', '2019-05-03',
  				 '2019-05-06', '2019-07-15', '2019-08-12', '2019-09-16', '2019-09-23', '2019-10-14', '2019-11-04', '2019-12-23',
  				 '2019-12-30', '2019-12-31',
  				 '2020-01-01', '2020-01-02', '2020-01-03', '2020-01-13', '2020-02-11', '2020-03-20', '2020-04-29', '2020-05-04',
  				 '2020-05-05', '2020-05-06', '2020-07-20', '2020-08-11', '2020-09-21', '2020-09-22', '2020-10-12', '2020-11-03',
  				 '2020-11-23', '2020-12-23', '2020-12-30', '2020-12-31'
   				];

  $("#day11,#day12,#day13").datepicker({
      //numberOfMonths: [1,2],
	  minDate:"+1d",//1日後～1ヶ月後まで選択可能
	  maxDate:"1m",
      beforeShowDay: function(date) {

      // 祝日の判定
      for (var i = 0; i < holidays.length; i++) {
        var htime = Date.parse(holidays[i]);  // 祝日を 'YYYY-MM-DD' から time へ変換
        var holiday = new Date();
        holiday.setTime(htime);         // 上記 time を Date へ設定

        // 祝日
        if (holiday.getYear() == date.getYear() &&
          holiday.getMonth() == date.getMonth() &&
          holiday.getDate() == date.getDate()) {
          return [false, 'holiday'];
        }
      }
      // 日曜日
      if (date.getDay() == 0) {
        return [false, 'sunday'];
      }
      // 土曜日
      if (date.getDay() == 6) {
        return [false, 'saturday'];
      }
      // 平日
      return [true, ''];
    },
    onSelect: function(dateText, inst) {
      $("#date_val").val(dateText);
    }
  });
});
</script>
<script type="text/javascript">
function keisan(){
	// 支払回数
	var number_of_payments = document.form1.number_of_payments.value ;
	if(number_of_payments>0){
		// 申込金額
		var payment_loan = '<?php echo $payment_loan;?>';
		// 分割払手数料
		//var total_installment_commission = Math.round(payment_loan*0.0082*number_of_payments);
		switch(Number(number_of_payments)){
			case 3:
				var total_installment_commission = Math.round(payment_loan*0.0225);
				break;
			case 6:
				var total_installment_commission = Math.round(payment_loan*0.0450);
				break;
			case 10:
				var total_installment_commission = Math.round(payment_loan*0.075);
				break;
			case 12:
				var total_installment_commission = Math.round(payment_loan*0.09);
				break;
			case 15:
				var total_installment_commission = Math.round(payment_loan*0.1125);
				break;
			case 18:
				var total_installment_commission = Math.round(payment_loan*0.135);
				break;
			case 19:
				var total_installment_commission = Math.round(payment_loan*0.1425);
				break;
			case 20:
				var total_installment_commission = Math.round(payment_loan*0.15);
				break;
			case 24:
				var total_installment_commission = Math.round(payment_loan*0.18);
				break;
			case 30:
				var total_installment_commission = Math.round(payment_loan*0.225);
				break;
			case 36:
				var total_installment_commission = Math.round(payment_loan*0.27);
				break;
			case 42:
				var total_installment_commission = Math.round(payment_loan*0.315);
				break;
			case 48:
				var total_installment_commission = Math.round(payment_loan*0.36);
				break;
			case 54:
				var total_installment_commission = Math.round(payment_loan*0.405);
				break;
			case 60:
				var total_installment_commission = Math.round(payment_loan*0.45);
				break;
			default:
				var total_installment_commission =0;
		}
		document.form1.total_installment_commission.value = total_installment_commission;
		// 分割支払金合計
		var amount_of_installments = Number(payment_loan) + Number(total_installment_commission);
		document.form1.amount_of_installments.value = amount_of_installments;
		// 第２回支払額
		var installment_amount_2nd_100 = Math.floor(amount_of_installments/number_of_payments/100);
		var installment_amount_2nd = installment_amount_2nd_100*100;
		document.form1.installment_amount_2nd.value = installment_amount_2nd;
		// 第1回支払額
		var installment_amount_1st = Number(amount_of_installments) - Number(installment_amount_2nd)*(Number(number_of_payments)-1);
		document.form1.installment_amount_1st.value = installment_amount_1st;
		// 年間請求予定額
		if(Number(number_of_payments)<=12){
			var annual_amount = amount_of_installments;
		} else {
			var annual_amount = Number(installment_amount_1st) + Number(installment_amount_2nd)*11;
		}
		document.form1.annual_amount.value = annual_amount;
		// 支払総額
		var initial_payment = '<?php echo $initial_payment;?>';
		var total_amount = Number(payment_loan)+Number(initial_payment)+Number(total_installment_commission);
		document.form1.total_amount.value = total_amount;
	}else{
		document.form1.total_installment_commission.value = '';
		document.form1.amount_of_installments.value = '';
		document.form1.installment_amount_2nd.value = '';
		document.form1.installment_amount_1st.value = '';
		document.form1.annual_amount.value = '';
	}
}
</script>
<script type="text/javascript">
function keisan0(){
	// 支払回数
	var number_of_payments = document.form1.number_of_payments.value;
	// 支払初月
    var first_payment_year = document.form1.first_payment_year.value;
    var first_payment_month = document.form1.first_payment_month.value;

    if (first_payment_year.length >= 1 && first_payment_month.length >= 1 && number_of_payments.length >= 1) {
        var date = new Date(convertInt(first_payment_year), (convertInt(first_payment_month) - 2) + convertInt(number_of_payments),0, 00, 00);
        var expected_end_year = date.getFullYear();
        var expected_end_month = date.getMonth() + 2;
        document.form1.expected_end_ym.value = expected_end_year + '年' + expected_end_month + '月';
        document.form1.expected_end_year.value = expected_end_year;
        document.form1.expected_end_month.value = expected_end_month;
    } else {
        document.form1.expected_end_year.value = '';
        document.form1.expected_end_month.value = '';
    }
}
</script>

<!-- start content-outer -->
<div id="content-outer">
<?php if(!$data['id'] && $contract['id'] && ($contract['status']<>0 || $contract['loan_company_id']<>8)){?>
	<div style="text-align: center;font-size:20px;padding-top:80px;">※　申込商品が正しくありません。</div>
	<div style="text-align: center;font-size:12px;padding-top:30px;">プラン変更された場合、次の予約を取ってからローン申込をしてください。</div>
	<div style="text-align: center;font-size:12px;padding-top:20px;">ローン取消で契約待ちの場合、ローン申込金額を入力してレジ精算してからローン申込してください。</div>
<?php }else{?>
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<!-- start id-form -->
			<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf_loan_application2();">
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
				<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
				<input type="hidden" name="contract_id" value="<?php echo $contract["id"];?>" />
				<input type="hidden" name="course_id" value="<?php echo $contract["course_id"];?>" />
				<input type="hidden" name="loan_company_id" value="<?php echo h($loan_company_id);?>" />
			<h1>
				ローン申込<?php echo $gMsg;?>
				<span style="float:right;margin-right:20px;font-size:4mm">担当者：
					<select name="staff_id" style="height:27px;">
						<?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , Post2Data($data['staff_id'],'staff_id'),getDatalist6("shop",$data['shop_id']));?>
					</select>
				</span>
			</h1>
		</div>
		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
			<tr>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
				<th class="topleft"></th>
				<td id="tbl-border-top">&nbsp;</td>
				<th class="topright"></th>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
			</tr>
			<tr>
				<td id="tbl-border-left"></td>
				<td>
					<!-- start content-table-inner -->
					<div id="content-table-inner">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
									<td>
										<table border="0" cellpadding="0" cellspacing="0" id="id-form">
											<tr>
												<th valign="top">申込日 <font size=+1 color=red>*</font>:</th>
												<td>
													<input class="inp-form" name="application_date" type="text" id="day" value="<?php echo Post2Data($data['application_date'],'application_date');?>" readonly />
													<font color="red" size="2">【重要】プラン変更・組換でお申込されるお客様は、</font>
												</td>
											</tr>
											<tr>
												<th valign="top">申込ローン会社:</th>
												<td>
													<select id="loan_company_id" name="loan_company_id" class="styledselect_form_3" disabled ><?php Reset_Select_Key( $loan_company_list , $loan_company_id);?></select>
													<font color="red" size="2" >下記のお客様情報に変更がある場合、必ず最新の情報をご入力下さい。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">申込店舗 <font size=+1 color=red>*</font>:</th>
												<td>
													<select id="shop_id" name="shop_id" class="styledselect_form_3" ><?php Reset_Select_Key_ShopGroup( $shop_lists ,$shop_id, $gArea_Group, "area_group"); ?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">お名前:</th>
												<td><input id="name" name="name" value="<?php echo Post2Data($customer['name'],'name');?>" class="inp-form" />
												</td>
											</tr>
											<tr>
												<th valign="top">フリガナ:</th>
												<td>
													<input id="name_kana" name="name_kana" value="<?php echo Post2Data($customer['name_kana'],'name_kana');?>" class="inp-form" />
												</td>
											</tr>
											<tr>
												<th valign="top">メールアドレス:</th>
												<td><input id="mail" name="mail" value="<?php echo Post2Data($customer['mail'],'mail');?>" class="inp-form" />
												<font color="red">※必ずご使用中のメールアドレスを入力して下さい。</font></td>
											</tr>
											<tr>
												<th valign="top">電話番号:</th>
												<td><input id="tel" name="tel" value="<?php echo Post2Data($customer['tel'],'tel');?>" class="inp-form" id="fm" /></td>
											</tr>
											<tr>
												<th valign="top">生年月日:</th>
												<td><input class="inp-form" name="birthday" type="text" id="day2" value="<?php echo Post2Data($customer['birthday'],'birthday');?>" readonly /></td>
											</tr>
											<tr>
												<th valign="top">郵便番号:</th>
												<td><input id="zip" name="zip" value="<?php echo Post2Data($customer['zip'],'zip');?>" class="inp-form" id="fm2" /></td>
											</tr>
											<tr>
												<th valign="top">都道府県:</th>
												<td><select name="pref" class="styledselect_form_1"><?php Reset_Select_Key( $gPref , Post2Data($customer['pref'],'pref'));?></select></td>
											</tr>
											<tr>
												<th valign="top">住所:</th>
												<td><textarea name="address" class="form-textarea2"><?php echo Post2Data($customer['address'],'address') ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">申込商品:</th>
												<td><input value="<?php echo TA_Cook($course['name']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">コース回数:</th>
												<td><input value="<?php echo TA_Cook($course['times']);?>" class="inp-form" readonly /></td>
											</tr>

											<tr>
												<th valign="top">商品金額:</th>
												<td><input value="<?php echo number_format($price);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">頭金:</th>
												<td><input name="initial_payment" value="<?php echo TA_Cook($initial_payment);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">申込金額:</th>
												<td><input name="amount" value="<?php echo TA_Cook($payment_loan);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">支払回数 <font size=+1 color=red>*</font>:</th>
												<td>
													<select id="number_of_payments" name="number_of_payments" class="styledselect_form_3" onChange="keisan()" ><?php Reset_Select_Key( $array_number_of_payments_ryfety , Post2Data($data['number_of_payments'],'number_of_payments'));?></select>
													<!--<font color="red">分割払手数料等を再計算されたい場合は、こちらを変更してください。</font>-->
												</td>
											</tr>
											<tr>
												<th valign="top">分割払手数料:</th>
												<td><input id="total_installment_commission" name="total_installment_commission" value="<?php echo TA_Cook($data['total_installment_commission']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">分割支払金合計:</th>
												<td><input id="amount_of_installments" name="amount_of_installments" value="<?php echo TA_Cook($data['amount_of_installments']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">第１回支払額:</th>
												<td><input id="installment_amount_1st" name="installment_amount_1st" value="<?php echo TA_Cook($data['installment_amount_1st']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">第２回支払額:</th>
												<td><input id="installment_amount_2nd" name="installment_amount_2nd" value="<?php echo TA_Cook($data['installment_amount_2nd']);?>" class="inp-form" readonly /></td>
											</tr>
											<input type="hidden" id="annual_amount" name="annual_amount" value="<?php echo $data["annual_amount"];?>" />
											<tr>
												<th valign="top">支払総額:</th>
												<td><input id="total_amount" value="<?php echo TA_Cook($total_amount);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">役務期間(自) :</th>
												<td>
													<input class="inp-form" name="service_start" type="text" id="day3" value="<?php echo Post2Data($service_start,'service_start');?>" readonly />
													<font color="red" size="2">※上記申込日よりも以前になる場合は、申込日に合わせてください。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">役務期間(至):</th>
												<td><input class="inp-form" name="service_end" type="text" id="day4" value="<?php echo Post2Data($service_end,'service_end');?>" readonly /></td>
											</tr>
											<tr>
												<th valign="top">預貯金額:</th>
												<td>
													<input name="save_amount" value="<?php echo Post2Data($data['save_amount'],'save_amount');?>" class="inp-form" />万円
													<font color="red">※無しの場合はゼロを入力してください。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">副収入種別:</th>
												<td>
													<select id="side_job" name="side_job" class="styledselect_form_3" >
														<?php Reset_Select_Key( $array_side_job,Post2Data($data['side_job'],'side_job')	);?>
													</select>
												</td>
											</tr>
											<tr>
												<th valign="top">副収入の年額:</th>
												<td>
													<input name="side_income" value="<?php echo Post2Data($data['side_income'],'side_income');?>" class="inp-form" />万円
													<font color="red">※無しの場合はゼロを入力してください。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">家賃負担:</th>
												<td>
													<select id="payment_lent" name="payment_lent" class="styledselect_form_3" >
														<?php Reset_Select_Key( $array_payment_lent,Post2Data($data['payment_lent'],'payment_lent')	);?>
													</select>
												</td>
											</tr>
											<!--<tr>
												<th valign="top">支払初月 <font size=+1 color=red>*</font>:</th>
												<td>
													<select id="first_payment_year" name="first_payment_year" style="height:27px;" onChange="keisan2()" >
														<?php Reset_Select_Key( $array_first_payment_year , Post2Data($data['first_payment_year'],'first_payment_year'));?>
													</select>年
													<select id="first_payment_month" name="first_payment_month" style="height:27px;" onChange="keisan2()" >
														<?php Reset_Select_Key( $array_first_payment_month , Post2Data($data['first_payment_month'],'first_payment_month'));?>
													</select>月
												</td>
											</tr>
											<tr>
												<th valign="top">支払終了予定月:</th>
												<td><input id="expected_end_ym" name="expected_end_ym" value="<?php echo $data['expected_end_year'] ? $data['expected_end_year'].'年'. $data["expected_end_month"].'月' : '' ;?>" class="inp-form" readonly /></td>
												<input type="hidden" id="expected_end_year" name="expected_end_year" value="<?php echo $data["expected_end_year"];?>" />
												<input type="hidden" id="expected_end_month" name="expected_end_month" value="<?php echo $data["expected_end_month"];?>" />
											</tr>-->
											<!--<tr>
												<th valign="top">支払方法 <font size=+1 color=red>*</font>:</th>
												<td>
													<select id="transfer_status" name="transfer_status" class="styledselect_form_3" ><?php Reset_Select_Key( $array_transfer_status , 
													Post2Data($data['transfer_status'],'transfer_status')
													);?></select>
													<font color="red">口座振替の場合、別途口座振替用紙でのお手続きが必要です。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">分割払手数料:</th>
												<td><input id="total_installment_commission" name="total_installment_commission" value="<?php echo TA_Cook($data['total_installment_commission']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">分割支払金合計:</th>
												<td><input id="amount_of_installments" name="amount_of_installments" value="<?php echo TA_Cook($data['amount_of_installments']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">第１回支払額:</th>
												<td><input id="installment_amount_1st" name="installment_amount_1st" value="<?php echo TA_Cook($data['installment_amount_1st']);?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">第２回支払額:</th>
												<td><input id="installment_amount_2nd" name="installment_amount_2nd" value="<?php echo TA_Cook($data['installment_amount_2nd']);?>" class="inp-form" readonly /></td>
											</tr>
											<input type="hidden" id="annual_amount" name="annual_amount" value="<?php echo $data["annual_amount"];?>" />
											<tr>
												<th valign="top">支払総額:</th>
												<td><input id="total_amount" value="<?php echo TA_Cook($contract['price']-$contract['balance']+$data['total_installment_commission']);?>" class="inp-form" readonly /></td>
											</tr>
											
											<tr>
												<th valign="top">お住まい <font size=+1 color=red>*</font>:</th>
												<td><select id="house_type" name="house_type" class="styledselect_form_3" ><?php Reset_Select_Key( $array_house_type , 
													Post2Data($data['house_type'],'house_type')
													);?></select><font color="red">※家賃をご家族が負担されている場合は「実家」を選択ください。</font></td>
											</tr>
											<tr>
												<th valign="top">生活費の援助 <font size=+1 color=red>*</font>:</th>
												<td><select id="living_grant" name="living_grant" class="styledselect_form_3" ><?php Reset_Select_Key( $array_living_grant , 
													Post2Data($data['living_grant'],'living_grant')
													);?></select><font color="red">ご家族(ご両親・配偶者様等)からの生活費の援助(仕送り・家賃等)の有無</font></td>
											</tr>
											<tr>
												<th valign="top">同一生計人数 <font size=+1 color=red>*</font>:</th>
												<td>
													<select id="same_living_count" name="same_living_count" class="styledselect_form_3" ><?php Reset_Select_Key( $array_same_living_count , Post2Data($data['same_living_count'],'same_living_count')
												);?></select>
													<font color="red">あなたの収入で生活をしている人数です。(ご自身を含む)</font>
												</td>
											</tr>
											<tr>
												<th valign="top">年収 <font size=+1 color=red>*</font>:</th>
												<td>
													<input id="annual_income" name="annual_income" value="<?php echo Post2Data($data['annual_income'],'annual_income');?>" class="inp-form" />万円
													<font color="red">税込の年収をご入力ください。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">本人確認書類:</th>
												<td><select id="identification_type" name="identification_type" class="styledselect_form_3" ><?php Reset_Select_Key( $array_identification_type , Post2Data($data['identification_type'],'identification_type')
												);?></select></td>
											</tr>
											<tr>
												<th valign="top">運転免許証番号:</th>
												<td><input id="identification_number" name="identification_number" value="<?php echo Post2Data($data['identification_number'],'identification_number')
												;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">本人確認方法:</th>
												<td>
													<input value="オンライン本人確認" class="inp-form" readonly />
													<font color="red">※オンライン本人確認は、KIREIMOマイページから24時間いつでも可能です。マイページをご確認いただけない場合は、お電話での本人確認となりますのでご了承ください。</font>
												</td>
											</tr>
											<tr>
												<th valign="top">電話連絡可能時間帯:</th>
												<td>
													<select id="call_timezone" name="call_timezone" class="styledselect_form_3" ><?php Reset_Select_Key( $array_call_timezone , Post2Data($data['call_timezone'],'call_timezone') );?></select>
													<font color="red">審査状況によってはお電話でご本人確認を行う場合がございます。比較的お電話に出やすい時間帯を、平日11:00から19:00の間で選択してください。</font>
											</td>
											</tr>-->
											<!--<tr>
												<th valign="top">ベリファイ日時1 <font size=+1 color=red>*</font>:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr valign="top">
															<td>
																<input class="inp-form" name="verify_datetime_date1" type="text" id="day11" value="<?php echo Post2Data($data['verify_datetime_date1'],'verify_datetime_date1')
																;?>" readonly />
																<font color="red">ご本人確認のご希望時間を、平日11:00から19:00の間で３候補選択してください。</font>
																<select id="verify_datetime_time1" name="verify_datetime_time1" class="styledselect_form_1" ><?php Reset_Select_Key( $array_verify_datetime_time , Post2Data($data['verify_datetime_time1'],'verify_datetime_time1'));?></select>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th valign="top">ベリファイ日時2:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr valign="top">
															<td>
																<input class="inp-form" name="verify_datetime_date2" type="text" id="day12" value="<?php echo Post2Data($data['verify_datetime_date2'],'verify_datetime_date2');?>"  />
																<select id="verify_datetime_time2" name="verify_datetime_time2" class="styledselect_form_1" ><?php Reset_Select_Key( $array_verify_datetime_time , Post2Data($data['verify_datetime_time2'],'verify_datetime_time2'));?></select>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th valign="top">ベリファイ日時3:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr valign="top">
															<td>
																<input class="inp-form" name="verify_datetime_date3" type="text" id="day13" value="<?php echo Post2Data($data['verify_datetime_date3'],'verify_datetime_date3');?>"  />
																<select id="verify_datetime_time3" name="verify_datetime_time3" class="styledselect_form_1" ><?php Reset_Select_Key( $array_verify_datetime_time , Post2Data($data['verify_datetime_time3'],'verify_datetime_time3'));?></select>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th valign="top">クレジットお申し込み<br />の内容同意 <font size=+1 color=red>*</font>:</th>
												<td>
													<textarea class="form-textarea4" style="overflow:auto;" readonly><?php include_once('credit_app_agree.txt') ;?></textarea><br />
													<input type="checkbox" name="credit_app_agree" value="1" id="credit_app_agree" <?php if(Post2Data($data['credit_app_agree'],'credit_app_agree')) echo "checked"?> >上記条項に同意する
												</td>
											</tr>
											<tr>
												<th valign="top">個人情報の取り扱い<br />に関する同意 <font size=+1 color=red>*</font>:</th>
												<td>
													<textarea class="form-textarea4" readonly><?php include_once('privacy_agree.txt') ;?></textarea><br />
													<input type="checkbox" name="privacy_agree" value="1" id="privacy_agree" <?php if(Post2Data($data['privacy_agree'],'privacy_agree')) echo "checked"?> >上記条項に同意する
												</td>
											</tr>-->
											<tr>
												<th><font size=-1 color=red>*は必須項目です。</font></th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
											</tr>
										</table>
									</td>
									<td>
										<!-- start related-activities -->
										<div id="related-activities">
											<!-- start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title"><a href="" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->

											<!-- start related-act-bottom -->
											<div id="related-act-bottom">
												<!-- start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>名前 : <?php echo $customer['name'] ? $customer['name'] : $customer['name_kana']?></h5></div>
													<div class="clear"></div>
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									</td>
								</form><!-- end id-form -->
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div><!-- end content-table-inner -->
				</td>
				<td id="tbl-border-right"></td>
			</tr>
			<tr>
				<th class="sized bottomleft"></th>
				<td id="tbl-border-bottom"></td>
				<th class="sized bottomright"></th>
			</tr>
		</table>
		<div class="clear">&nbsp;</div>
	</div>
	<!-- end content -->
<?php } ?>
	<div class="clear">&nbsp;</div>
</div>
<!-- end content-outer -->
<?php include_once("../include/footer.html");?>
