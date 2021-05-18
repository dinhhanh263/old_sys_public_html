<?php include_once("../library/service/detail.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<?php if($sales['option_name']<>4){?>
<style type="text/css">
	#option_year{ display:none;}
	#option_month{ display:none;}
	#option_month2{ display:none;}
</style>
<?php } ?>
<?php if(!$sales['payment_loan']){?>
<style type="text/css">
	#loan_company{ display:none;}
</style>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){
	if(document.getElementById("g_error") != null){
	document.form1.option_name.options[3].selected = true;
	keisan();
	} else {
		// 未精算時(!sales_id)にオプション項目の初期表示処理を行う(実質月額支払時のみ)
		var option_name_flg = '<?php echo $option_name_flg;?>';
		if(option_name_flg) {
			keisan();
		}
	}
});

function keisan(){
	var option_name = document.form1.option_name.selectedIndex ; 
	var option_name_shot = document.form1.option_name.value;
    var tax = <?php echo $tax2; ?>

	var point = '<?php echo $point;?>';
	if(option_name==1) $('#option_name').html("シェーピング");
	else if(option_name==2) $('#option_name').html("店舗移動費");
	else if(option_name==3) $('#option_name').html("月額支払");
	else if(option_name==4) $('#option_name').html("延滞手数料");
	else if(option_name==5) $('#option_name').html("DigiCatキャンセル手数料");
	else if(option_name==6) $('#option_name').html("お顔脱毛体験料");
	else if(option_name==7) $('#option_name').html("ヒザ下3回1500円");
	else if(option_name==8) $('#option_name').html("ヒザ下1回500円");
	else if(option_name==9) $('#option_name').html("両ワキ脱毛3回500円");
	else $('#option_name').html("オプション");
	
	if (option_name==3){
		var option_price =0;
		$('#option_year').css("display","table-row");
		$('#option_month').css("display","table-row");
		$('#option_month2').css("display","table-row");
	}else if (option_name ==1 || option_name ==2  ){
		var option_price =1000;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name ==5 ){
		var option_price =500;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name ==6 ){
		var option_price =500-point;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name ==7 ){
		var option_price =2500-point;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name ==8 ){
		var option_price =1500-point;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name ==9 ){
		var option_price =500-point;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name_shot ==14) {
		var option_price = Math.round(5000 * tax);
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name_shot ==15) {
		var option_price = Math.round(10000 * tax);
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name_shot ==16) {
		var option_price = Math.round(7500 * tax);
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name_shot ==17) {
		var option_price = Math.round(9800 * tax);
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else if (option_name_shot ==18) {
		var option_price = Math.round(5000 * tax) + Math.round(10000 * tax);
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	}else{
		var option_price = 0;
		$('#option_year').css("display","none");
		$('#option_month').css("display","none");
		$('#option_month2').css("display","none");
	} 
	document.form1.option_price.value = option_price; // オプション金額を表示、現金
	var option_transfer = document.form1.option_transfer.value ; // オプション金額を表示、振込
	var option_card = document.form1.option_card.value ; // オプション金額を表示、カード

	var option_price0 = Number(option_price) + Number(option_transfer)　+ Number(option_card);

	var option_price2 = String( option_price0 ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#option_price').html("￥" + option_price2);

	var f_payment = document.form1.payment_cash.value ; // 入金(現金)
	var c_payment = document.form1.payment_card.value ; // 入金(カード)
	var t_payment = document.form1.payment_transfer.value ; // 入金(振込)
	var l_payment = document.form1.payment_loan.value ; // 入金(ローン)

	if(Number(l_payment)>0){
		$('#loan_company').css("display","table-row"); //ローン会社プルダウン表示
	}else{
		$('#loan_company').css("display","none"); //ローン会社プルダウン非表示
	}

	var payment = Number(f_payment) + Number(c_payment)　+ Number(t_payment) + Number(l_payment);
	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment2);

	var total = Number(payment) + Number(option_price0);
	var total2 = String( total ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#total').html("￥" + total2);

	var price = document.form1.price.value;
	
	var balance = Number(price) - Number(payment)
	var balance2 = String( balance ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#balance').html("￥" + balance2);
	
	
}
</script>
<script type="text/javascript">
function keisan2(){

	option_price = document.form1.option_price.value ; // オプション金額を表示、現金
	var option_transfer = document.form1.option_transfer.value ; // オプション金額を表示、振込
	var option_card = document.form1.option_card.value ; // オプション金額を表示、カード

	var option_price0 = Number(option_price) + Number(option_transfer)　+ Number(option_card);
	var option_price2 = String( option_price0 ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#option_price').html("￥" + option_price2);

	var f_payment = document.form1.payment_cash.value ; // 入金(現金)
	var c_payment = document.form1.payment_card.value ; // 入金(カード)
	var t_payment = document.form1.payment_transfer.value ; // 入金(振込)
	var l_payment = document.form1.payment_loan.value ; // 入金(カード)

	var payment = Number(f_payment) + Number(c_payment)+ Number(t_payment)+ Number(l_payment);
	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment2);

	var total = Number(payment) + Number(option_price0)　;
	var total2 = String( total ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#total').html("￥" + total2);

	var price = document.form1.price.value;
	var balance = Number(price) - Number(payment) ;
	var balance2 = String( balance ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#balance').html("￥" + balance2);
}
</script>
<script type="text/javascript">
function keisan3(){
	if(document.form1.keep.value>0){
		document.form1.change.value = document.form1.keep.value - document.form1.payment_cash.value - document.form1.payment_card.value - document.form1.payment_transfer.value - document.form1.payment_loan.value - document.form1.option_price.value; // お釣りを表示
	}
}
</script>

<script type="text/javascript">
function keisan4(){
	var dt = new Date();
	var year = dt.getFullYear() ; // 現在の年
    var month = dt.getMonth() +1; // 現在の月
	var foption_year = document.form1.option_year.value ;   // 何年分支払代
	var foption_month = document.form1.option_month.value ; // 何月分支払代金

	// カンマ区切りの月で一番小さい月を取得する
	result = foption_month.split( "," );
	foption_month = Math.min.apply(null, result);

	// 3ヶ月前以前のチェック
	if(foption_year != '-' && foption_month != ''){
		// 入力した年が今年と同じか、今年より前の年のとき
		if( foption_year <= year ){
			if(4 < month){ // 4-12月まで
			var out_month = month -3; // 4月以降、3ヶ月前
				// 入力した年が今年と同じで、3か月前になっているとき
				if(foption_year == year && foption_month < out_month) {
				optionerror();
			} else if(foption_year < year){
				optionerror();
			}
			} else if(month < 4) { // 1-3月まで
			var out_month = month +9; // 4月以前、3ヶ月前
				// 入力した年が去年と同じで、かつ3ヶ月前の月になっているとき
				if(foption_year == (year -1) && foption_month < out_month) {
				optionerror();
				}else if(foption_year < (year -1)){
				optionerror();
			}
		} 

		}	
	} 	
}

function optionerror(){
	alert('3ヶ月よりも前の日付が入力されています。確認してください。');
}

</script>

<!--全角英数字、ハイフン->半角--> 
<script type="text/javascript"> 
$(function() {
  $('#fm2').change(function(){
    var result  = $(this).val();
    for(var i = 0; i < result.length; i++){
        var char = result.charCodeAt(i);
        if(char >= 0xff10 && char <= 0xff19 ){
            //全角数値なら
            result = result.replace(result.charAt(i),String.fromCharCode(char-0xfee0));
        }
        if(char == 0xff0d || char == 0x30fc || char == 0x2015 || char == 0x2212){
            //全角ハイフンなら
            result = result.replace(result.charAt(i),String.fromCharCode(0x2d));
        }
    }
    $(this).val(result);
  });
});
</script>


<!-- start content-outer -->
<div id="content-outer">
<?php if(!$data['id'] && $sales['reservation_id']){?>
	<div style="text-align: center;font-size:20px;padding-top:80px;">※　パラメータが正しくありません。</div>
	<div style="text-align: center;font-size:12px;padding-top:30px;">売上に予約IDが紐付いています。予約詳細からレジ精算へ遷移してください。</div>
<?php }else{?>
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>レジ精算　　<?php echo ($gMsg) ;?></h1></div>
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
					<!--  start content-table-inner -->
					<div id="content-table-inner">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<!-- start id-form -->
								<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf_detail('');">
									<input type="hidden" name="action" value="edit" />
									<input type="hidden" name="reservation_id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="contract_id" value="<?php echo $contract["id"];?>" />
									<input type="hidden" name="times" value="<?php echo $contract["times"];?>" />
									<input type="hidden" name="r_times" value="<?php echo $contract["r_times"];?>" />
									<input type="hidden" name="course_id" value="<?php echo $contract["course_id"];?>" />
									<input type="hidden" name="course_type" value="<?php echo $course["type"];?>" />
									<input type="hidden" name="new_flg" value="<?php echo $course["new_flg"];?>" /><!-- 新月額フラグ -->
									<input type="hidden" name="zero_flg" value="<?php echo $course["zero_flg"];?>" />
									<input type="hidden" name="fixed_price" value="<?php echo $contract["fixed_price"];?>" />
									<input type="hidden" name="discount" value="<?php echo $contract["discount"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" />
									<input type="hidden" name="type" value="<?php echo $kbn;?>" />
									<input type="hidden" name="pay_date" value="<?php echo $data["hope_date"];?>" />
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select class="styledselect_form_3" disabled><?php Reset_Select_Key( $gResType3 , $kbn);?></select></td>


												<td rowspan="20">
													<div style="float:left;padding:40px;">
														<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
															<tr><td colspan="2"><h1>ご明細</h1></td></tr>
															<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
															<tr>
																<td class="regTotal"><?php echo $course_list[$contract['course_id']];?></td>
																<td class="regTotalPrice">￥<?php echo number_format($contract['fixed_price']);?></td>
															</tr>
														<?php if($sales['discount']){?>
															<tr>
																<td class="reg">値引き</td>
																<td class="regPrice">▲￥<?php echo number_format($contract['discount']);?></td>
															</tr>
															<tr>
																<td class="regTotal">コース金額（値引後）</td>
																<td class="regTotalPrice">￥<?php echo number_format($sales['fixed_price'] - $sales['discount']);?></td>
															</tr>
														<?php } ?>
														<?php if ($kbn != 2 && $kbn != 8 && $kbn != 14) { ?>
															<tr>
																<td class="reg">残金支払</td>
																<td id="payment" class="regPrice">￥<?php echo number_format($sales['payment']);?></td>
															</tr>
														<?php } ?>
															<tr>
																<td id="option_name" class="reg"><?php echo $option_name ? $gOption[$option_name] : "オプション";?></td>
																<td id="option_price" class="regPrice">￥<?php echo number_format($sales['option_price']);?></td>
															</tr>
															<tr>
																<td class="regTotal">支払合計</td>
																<td id="total" class="regTotalPrice">￥<?php echo number_format($sales['payment'] + $sales['option_price'] + $sales['option_transfer'] + $sales['option_card']);?></td>
															</tr>
														<?php if ($kbn != 2 && $kbn != 8 && $kbn != 14) { ?>
															<tr>
																<td class="reg">支払後残金</td>
																<td id="balance" class="regPrice">￥<?php echo number_format($sales['balance']);?></td>
															</tr>
														<?php } ?>
															<tr><td></td></tr>
															<tr>
																<td class="reg">消化(来店)回数</td>
																<td id="r_times" class="regPrice"><?php echo number_format($contract['r_times']);?>回</td>
															</tr>
															<tr>
																<td class="reg">消化単価</td>
																<td id="per_price" class="regPrice">￥<?php echo number_format($price_once);?></td>
															</tr>
															<?php if($course["type"] ==0 ){ ?>
															<tr>
																<td class="reg">残回数</td>
																<td id="remain_times" class="regPrice"><?php echo ($contract['times']-$contract['r_times'])<0 ? 0 : number_format($contract['times']-$contract['r_times']);?></td>
															</tr>
															<?php } ?>

															<tr><td></td></tr>
															<tr>
																<td class="reg">お預かり</td>
																<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan3()"/></td>
															</tr>
															<tr>
																<td class="reg">お釣り</td>
																<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
															</tr>


														</table>
														※「ヒザ下3回1500円」と「ヒザ下1回500円」にシェービング代が含まれています。
													</div>
												<td>

											</tr>

											<tr>
												<th valign="top">コース:</th>
												<td><input type="tel" value="<?php echo $course_list[$contract['course_id']];?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">コース金額（税込）:</th>
												<td><input type="tel" name="fixed_price" value="<?php echo number_format($contract['fixed_price']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">値引き:</th>
												<td><input type="tel" name="discount" value="<?php echo number_format($contract['discount']) ;?>"  class="inp-form" disabled /></td>
											</tr>
											<?php //if($contract['balance']){ ?>
											<?php if ($kbn != 2 && $kbn != 8 && $kbn != 14) { ?>
												<tr>
													<th valign="top">売掛金:</th>
													<td><input type="tel" name="price" value="<?php echo ($sales['id'] ? $sales['price'] : $contract['balance']) ;?>"  class="inp-form"  onChange="keisan()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(現金):</th>
													<td><input type="tel" name="payment_cash" value="<?php echo TA_Cook($sales['payment_cash']) ;?>" id="fm2" class="inp-form"  onChange="keisan()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(カード):</th>
													<td><input type="tel" name="payment_card" value="<?php echo TA_Cook($sales['payment_card']) ;?>" id="fm2" class="inp-form"  onChange="keisan()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(振込):</th>
												<?php if($authority_level<=6){ ?>
													<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" id="fm2"  class="inp-form" onChange="keisan()" /></td>
												<?php }else{ ?>
													<td><input type="tel" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form-disable"  disabled /></td>
													<input id="t_payment" type="hidden" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" />
												<?php } ?>
												</tr>
												<tr>
													<th valign="top">残金支払(ローン):</th>
													<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($sales['payment_loan']) ;?>" id="fm2" class="inp-form" onChange="keisan()" /></td>
												</tr>
												<tr id="loan_company">
													<th valign="top">申込ローン会社:</th>
													<td>
														<select id="loan_company_id" name="loan_company_id" class="styledselect_form_3" ><?php Reset_Select_Key( $loan_company_list , $loan_company_id);?></select>
													</td>
												</tr>
												<!--ローン不備ステータス時のデザイン変更-->
												<?php if($contract['loan_status']==5){?>
												<tr>
													<th valign="top" <?php if($contract['loan_status']==5)echo 'class="attention_item_o"'; ?>>※ローン不備確認済:</th>
													<td><input type="checkbox" name="if_loan_deficiency" value="1" class="form-checkbox" /></td>
												</tr>
												<?php } else {?>
													<input type="hidden" name="if_loan_deficiency" value="0" class="form-checkbox" />
												<?php } ?>
											<?php } ?>
												<tr>
													<th valign="top">オプション:</th>
													<td><select name="option_name" class="styledselect_form_3" onChange="keisan()"><?php Reset_Select_Key( $gOption , $option_name);?></select></td>
												</tr>
												<br>
												<tr id="option_year">
													<th valign="top">何年支払代金:</th>
													<!--  年が選ばれていない場合は今年の年のプルダウンをセットする -->
													<?php $option_year = ($_POST['option_year'] == "" && $sales['option_year'] =="") ? date("Y") : $_POST['option_year'];?>
													<?php $option_year = ($option_year <> "") ? $option_year : TA_Cook($sales['option_year']);?>
													<td><select name="option_year" class="styledselect_form_3" onChange="keisan4()" ><?php Reset_Select_Val( $gOptionYear , $option_year);?></select></td>
												</tr>
												<tr id="option_month" >
													<th valign="top">何月分支払代金:</th>
													<td><input type="text" name="option_month" value="<?php echo $_POST['option_month']=($_POST['option_month']<>null) ? $_POST['option_month']: TA_Cook($sales['option_month']) ;?>" class="inp-form" onChange="keisan4()" placeholder="<?php echo date("n").','; echo date("n") =='12' ? '1': date("n")+1 ;?>" /></td>
												</tr>
												<tr id="option_month2" >
													<th valign="top">振替日:</th>
													<td><input type="text" name="option_date" value="<?php echo $sales['option_date']=="0000-00-00" ? $data['hope_date'] : (TA_Cook($sales['option_date'])) ;?>" class="inp-form" id="fm" placeholder="<?php echo date("Y-m-d");?>"/></td>
												</tr>
												<tr>
													<th valign="top">オプション金額(現金):</th>
													<td><input type="text" name="option_price" value="<?php echo TA_Cook($sales['option_price']) ;?>" id="fm2"  class="inp-form" onChange="keisan2()" /></td>
												</tr>
												<tr>
													<th valign="top">オプション金額(振込):</th>

												<?php if($authority_level<=6){ ?>
													<td><input type="text" name="option_transfer" value="<?php echo TA_Cook($sales['option_transfer']) ;?>"  id="fm2" class="inp-form" onChange="keisan2()" /></td>
												<?php }else{ ?>
													<td><input type="text" value="<?php echo TA_Cook($sales['option_transfer']) ;?>" class="inp-form-disable" disabled /></td>
													<input type="hidden" name="option_transfer" value="<?php echo TA_Cook($sales['option_transfer']) ;?>" />
												<?php } ?>
												</tr>
												<tr>
													<th valign="top">オプション金額(カード):</th>
													<td><input type="text" name="option_card" value="<?php echo TA_Cook($sales['option_card']) ;?>" id="fm2"  class="inp-form" onChange="keisan2()" /></td>
												</tr>
												<!--<tr>
													<th valign="top">支払方法:</th>
													<td><select name="pay_type" class="styledselect_form_1"><?php Reset_Select_Key( $gPayType , $sales['pay_type'] ? $sales['pay_type'] : 1);?></select></td>
												</tr>-->

											<!-- 月額で施術開始予定(年月)がある ※更新はできない-->
											<?php if ($course["type"] && $contract['start_ym'] && $kbn != 14 && $kbn != 11) { ?>
											<tr>
												<th valign="top">施術開始(年月):</th>
												<td>
													<input style="width:50px;height:21px;" id="limit_ym" name="start_ym" type="text" value="<?php echo $contract['start_ym']<>0 ? TA_Cook($contract['start_ym']):date('Ym') ;?>" readonly />
												</td>
											</tr>
											<?php } ?>
											<?php if ($kbn == 2 || $kbn == 3 || $kbn == 11 || $kbn == 14) { ?>
											<tr>
												<th valign="top">役務消化:</th>
												<!-- 2016/10/28 消化チェック時、エラーをする前にチェックを入れていた場合、エラーの時に外れないようにする -->
												<td><input type="checkbox" name="if_service" value="1" class="form-checkbox" 
												<?php if($sales['r_times']){
													echo "checked disabled";
												} elseif($_POST['if_service'] && !$sales['r_times']) { 
												    echo "checked";
												}
												?> /></td>
											</tr>
											<?php } ?>
											<tr>
												<th valign="top">売上非計上フラグ:</th>
												<!-- エラーをする前にチェックを入れていた場合、エラーの時に外れないようにする -->
												<td><input type="checkbox" name="non_record_flg" value="1" class="form-checkbox" 
												<?php if($sales['non_record_flg'] == 1) { 
												    echo "checked";
												} 
												?> /></td>
											</tr> 
										<?php if($course['type'] && $data['type']==2){?>
											<tr>
												<th valign="top">施術部位:</th>
												<td><select name="part" class="styledselect_form_3"><?php Reset_Select_Key( $gPart , $_POST['part'] ? $_POST['part']  : $data['part']);?></select></td>
											</tr>
										<?php } ?>
			
											<?php if($data['type']==2){ ?>
											<tr>
												<th valign="top">施術主担当:</th>
												<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">施術サブ担当1:</th>
												<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub1_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">施術サブ担当2:</th>
												<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub2_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 

											<?php }?>


											<tr>
												<th valign="top">レジ担当:</th>
												<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['rstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											
											<tr>
												<th valign="top">備考:</th>
												<td><textarea name="memo" class="form-textarea2"><?php echo TA_Cook($sales['memo']) ;?></textarea></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
											</tr>
										</table>
									</td>
									<td>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title"><a href="../customer/edit.php?customer_id=<?php echo $customer['id']?>"  style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94b52c'" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->
		
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>名前 : <?php echo $customer['name']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
												
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>カウンセリング担当 : <?php echo $staff_list[$contract['staff_id']]?></h5></div>
													<div class="clear"></div>
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									<?php if($sales['id']){?>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">出力</div>
											</div>
											<!-- end related-act-top -->
			
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
	
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right">
														<h5><a href="javascript:void(0);" onclick="window.open('../pdf/pdf_out.php?sales_id=<?php echo $sales['id'];?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
													</div>
													<div class="clear"></div>
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									<?php } ?>
									</td>
								</form><!-- end id-form  -->
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div><!--  end content-table-inner  -->
				</td>
				<td id="tbl-border-right"></td>
			</tr>
			<tr>
				<th class="sized bottomleft"></th>
				<td id="tbl-border-bottom">&nbsp;</td>
				<th class="sized bottomright"></th>
			</tr>
		</table>
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<?php } ?>
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<script type="text/javascript">
jQuery(function ($) {
    //登録完了時アラート
    let complete_flg = "<?php echo $complete_flg; ?>";
    if (complete_flg) {
        let sales_id = "<?php echo $sales['id']; ?>";
        alert('登録が完了しました。');
        location.href = `./detail.php?sales_id=${sales_id}`;
    }
    //登録完了後のブラウザバックを禁止
    let sales_param = "<?php echo $_GET['sales_id']; ?>";
    if (sales_param) {
        history.pushState(null, null, null);

        window.addEventListener("popstate", function() {
            history.pushState(null, null, null);
        });
		}
});
</script>
<?php include_once("../include/footer.html");?>
