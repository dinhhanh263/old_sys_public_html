<?php include_once("../library/account/reg_detail.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- 月のDATEPICKER を読み込むためのJS -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker-3monthonly.js"></script><!-- 選択範囲限定の施術開始年月を表示させるためのJS -->
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />
<?php if(!$sales['payment_loan']){?>
<style type="text/css">
	#loan_company{ display:none;}
</style>
<?php } ?>
<script type="text/javascript">
function keisan(){
	var course_id = document.form1.course_id.value;
	var json_course_list = '<?php echo $json_course_list; ?>';
	var json_course_price = '<?php echo $json_course_price; ?>';
	var course_list = JSON.parse(json_course_list);
	var course_price = JSON.parse(json_course_price);
	document.form1.fixed_price.value = course_price[course_id]; // コース金額（税込）
	// 月額：施術開始予定年月表示制御のメソッドを呼び出す 2016/10/18 shimada
	start_ym('#start_ym');

	var discount_rate = document.form1.discount_rate.selectedIndex ;
	if(discount_rate　==　1){
		document.form1.discount.value = Math.round(course_price[course_id] *　0.05);
	}else if (discount_rate　==　2 ){
		document.form1.discount.value = Math.round(course_price[course_id] *　0.1);
	}else if (discount_rate　==　3 ){
		document.form1.discount.value = Math.round(course_price[course_id] *　0.2);
	}

	var discount = document.form1.discount.value ;
	var price = course_price[course_id] - discount;
	document.form1.price.value = price; // 請求金額（税込）

	var f_payment = document.form1.payment_cash.value ; // 初回入金(現金)
	var c_payment = document.form1.payment_card.value ; // 初回入金(カード)
	var t_payment = document.form1.payment_transfer.value ; // 初回入金(振込)
	var l_payment = document.form1.payment_loan.value ; // 初回入金(ローン)

	if(Number(l_payment)>0){
		$('#loan_company').css("display","table-row"); //ローン会社プルダウン表示
	}else{
		$('#loan_company').css("display","none"); //ローン会社プルダウン非表示
	}

	//明細--------------------------------------------------------------------------------
	var course_name =  course_list[course_id];
	$('#course_name').html(course_name); 				  //コース名（明細）

	var course_price = String( course_price[course_id] ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#course_price').html("￥" + course_price);         //コース金額（明細）

	var option_name = document.form1.option_name.selectedIndex ;
	var free_flg = <?php echo $_POST['free_flg'] ? $_POST['free_flg'] : 0; ?>;
	if (free_flg == 1) {
		if(option_name==1) $('#option_name').html("バースデー無料施術1回");
		else if(option_name==2) $('#option_name').html("新規契約特典無料施術1回");
		else $('#option_name').html("オプション");
		var option_price = 0;
	} else {
		if(option_name==1) $('#option_name').html("シェーピング");
		else if(option_name==2) $('#option_name').html("店舗移動費");
		else if(option_name==3) $('#option_name').html("無料追加（全身1回）");
		else if(option_name==4) $('#option_name').html("フリーチケット（全身1回）");
		else $('#option_name').html("オプション");

		if (option_name ==1 || option_name ==2){
			var option_price =1000;
		}else{
			var option_price = 0;
		}
	}

	document.form1.option_price.value = option_price; // オプション金額を表示
	var option_price2 = String( option_price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#option_price').html("￥" + option_price2);

	var total = price + option_price;
	var total2 = String( total ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#total').html("￥" + total2);         //合計：請求金額（明細）

	var discount2 = String( discount ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#discount').html("▲￥" + discount2);         //値引き（明細）

	var tax2 = '<?php echo $tax2;?>';
	var duty = price - Math.round( price / tax2 );
	var tax = String( duty ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#tax').html("￥" + tax);         //内税（明細）

	// var payment = Number(f_payment) + Number(c_payment) + Number(t_payment) + Number(l_payment) + Number(p_payment);
	var payment = Number(f_payment) + Number(c_payment) + Number(t_payment) + Number(l_payment);
	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment2);         //入金金額（明細）

	var balance = total - payment ;
	var balance2 = String( balance ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#balance').html("￥" + balance2);         //入金金額（明細）

}
</script>

<script type="text/javascript">
function keisan3(){
	if(document.form1.keep.value>0){
		document.form1.change.value = document.form1.keep.value - document.form1.payment_cash.value - document.form1.payment_card.value - document.form1.payment_transfer.value - document.form1.payment_loan.value - document.form1.payment_coupon.value - document.form1.option_price.value; // お釣りを表示
	}
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
<script type="text/javascript">
$(function() {
  $('#fm3').change(function(){
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
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>レジ精算<?php echo ($_POST['free_flg'] ? "（無料プラン付与）" : "").$gMsg;?></h1></div>
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
								<form action="" method="post" name="form1" enctype="multipart/form-data" <?php echo $_POST['free_flg'] ? 'onSubmit="return conf1(\'\');"' : 'onSubmit="return conf_reg();"'?>>
									<input type="hidden" name="action" value="edit" />
									<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" /><!--契約店舗-->
									<input type="hidden" name="type" value="<?php echo $data["type"];?>" />
									<input type="hidden" name="pay_date" value="<?php echo $sales["pay_date"];?>" />
									<td>

										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled><?php Reset_Select_Key( $gResType4 , $data['type']);?></select></td>

												<td rowspan="20">
													<div style="float:left;padding:40px;">
														<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
															<select name="discount_rate" onChange="keisan()" <?php echo $_POST['free_flg'] ? "disabled" : "";?>><?php Reset_Select_Key( $gDiscountRate ,$_POST['discount_rate']);?></select>&nbsp;(割引率換算)<br /><br />
															<tr><td colspan="2"><h1>ご購入明細</h1></td></tr></tr>
															<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
															<tr>
																<td id="course_name" class="reg"><?php echo $sales['course_id'] ? $course_list[$sales['course_id']] : $course_list[$default_course_id];?></td>
																<td id="course_price" class="regPrice">￥<?php echo number_format($sales['fixed_price']);?></td>
															</tr>
															<tr>
																<td class="reg">値引き</td>
																<td id="discount" class="regPrice">▲￥<?php echo number_format($sales['discount']);?></td>
															</tr>
															<tr>
																<td id="option_name" class="reg"><?php echo ($_POST['free_flg'] && $contract['option_name']) ? $gFreeOption[$contract['option_name']] : ($sales['option_name'] ? $gOption[$sales['option_name']] : "オプション");?></td>
																<td id="option_price" class="regPrice">￥<?php echo number_format($sales['option_price']);?></td>
															</tr>
															<tr>
																<td class="regTotal">合計</td>
																<td id="total" class="regTotalPrice">￥<?php echo number_format($sales['price'] + $sales['option_price']);?></td>
															</tr>
															<tr>
																<td class="reg">内税（<?php echo $tax*100 ."%";?>）</td>
																<td id="tax" class="regPrice">￥<?php echo number_format($sales['price']-$sales['price']/$tax2);?></td>
															</tr>
															<tr>
																<td class="regTotal">入金金額</td>
																<td id="payment" class="regTotalPrice">￥<?php echo number_format($sales['payment']+ $sales['option_price']);?></td>
															</tr>
															<tr>
																<td class="reg">残金</td>
																<td id="balance" class="regPrice">￥<?php echo number_format($sales['balance']);?></td>
															</tr>
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
														<?php if($complete_flg){?>
															<div align="right"><a href="./?pay_date=<?php echo $_POST['pay_date'] ;?>">レジ一覧へ<a></div>
														<?php } ?>
													</div>

												<td>

											</tr>
											<tr>
												<th valign="top">コース:</th>
												<?php if($_POST['free_flg']) { ?>
												    <td><select name="course_id" class="styledselect_form_3" onChange="keisan()" title=""><option value="0">-</option><?php Reset_Select_Key_Group($course_list, ($contract['course_id'] ? $contract['course_id'] : $default_course_id), $gFreeCourseGroup);?></select></td>
												<?php } elseif($data['type'] == 33) { ?>
												    <td><select name="course_id" class="styledselect_form_3" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option value="0">-</option><?php Reset_Select_Key_Group( $course_list , ($sales['course_id'] ? $sales['course_id'] : ($data['hp_flg'] ? 70 : 0)),$gShotCourseGroup);?></select></td>
												<?php } else {?>
													<td><select name="course_id" class="styledselect_form_3" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option value="0">-</option><?php Reset_Select_Key_Group( $course_list , ($sales['course_id'] ? $sales['course_id'] : ($data['hp_flg'] ? 70 : 0)),$gCourseGroup);?></select></td>
												<?php } ?>
											</tr>
											<tr>
												<th valign="top">コース金額（税込）:</th>
												<?php if($_POST['free_flg']) { ?>
													<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($contract['fixed_price'] ? $contract['fixed_price'] : 0);?>" class="inp-form" readonly="readonly"/></td>
												<?php } else { ?>
													<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($sales['fixed_price'] ? $sales['fixed_price'] : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" readonly="readonly"/></td>
												<?php } ?>
											</tr>
											<tr>
												<th valign="top">値引き:</th>
												<td>
													<input type="text" name="discount" value="<?php echo TA_Cook($sales['discount'] ? $sales['discount'] : ($data['hp_flg'] ? ($data['point']+ $hp_discount) : ($_POST['free_flg'] ? 0 : ""))) ;?>" class="inp-form"  onChange="keisan()" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/>

												</td>
											</tr>
											<tr>
												<th valign="top">値引タイプ:</th>
												<td><select name="dis_type" class="styledselect_form_3" <?php echo $_POST['free_flg'] ? "disabled" : "";?>><?php Reset_Select_Key( $gDisType , $sales['dis_type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">(内)ポイント:</th>
												<td><input type="text" name="point" value="<?php echo TA_Cook($sales['point'] ? $sales['point'] : $data['point']) ;?>" class="inp-form" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/></td>
											</tr>
											<tr>
												<th valign="top">ご請求金額（税込）:</th>
												<?php if($_POST['free_flg']) { ?>
													<td><input type="text" name="price" value="<?php echo  TA_Cook($contract['price'] ? $contract['price'] : 0) ;?>" class="inp-form" readonly="readonly"/></td>
												<?php } else { ?>
													<td><input type="text" name="price" value="<?php echo  TA_Cook($sales['price'] ? $sales['price'] : ($data['hp_flg'] ? ($hp_price-$data['point']- $hp_discount) : "")) ;?>" class="inp-form" /></td>
												<?php } ?>
											</tr>
											<tr>
												<th valign="top">入金(現金):</th>
												<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo TA_Cook($sales['payment_cash']) ;?>" class="inp-form" onChange="keisan()" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/></td>
											</tr>
											<tr>
												<th valign="top">入金(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo TA_Cook($sales['payment_card']) ;?>" class="inp-form" onChange="keisan()" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/></td>
											</tr>
											<tr>
												<th valign="top">入金(振込):</th>
											<?php if($authority_level<=6){ ?>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form" onChange="keisan()" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/></td>
											<?php }else{ ?>
												<td><input type="tel" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form-disable"  disabled /></td>
												<input id="t_payment" type="hidden" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" />
											<?php } ?>
											</tr>
											<tr>
												<th valign="top">入金(ローン):</th>
												<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($sales['payment_loan']) ;?>" class="inp-form" onChange="keisan()" <?php echo $_POST['free_flg'] ? 'readonly="readonly"' : '';?>/></td>
											</tr>
											<tr id="loan_company">
												<th valign="top">申込ローン会社:</th>
												<td>
													<select id="loan_company_id" name="loan_company_id" class="styledselect_form_3" ><?php Reset_Select_Key( $loan_company_list , $loan_company_id);?></select>
												</td>
											</tr>
											<!-- 月額のみ表示 -->
											<tr id="start_ym" >
												<th valign="top">施術開始予定(年月):</th>
												<td><select name="start_ym" class="styledselect_form_3" >
													<?php if($contract['start_ym']<>0) {
																$hope_date_ym = date("Ym",strtotime("first day of " .$data['hope_date']));
																$hope_date_next_ym = date("Ym",strtotime("first day of " .$data['hope_date'] . "+1 month"));
																// hope_dateと月額施術開始年月が同じ
																if($contract['start_ym'] == $hope_date_ym){
																	echo '<option value="'. TA_Cook($contract['start_ym']).'" selected >'.TA_Cook(substr($contract['start_ym'],0,4).'年'.substr($contract['start_ym'],4,2).'月').'</option>';
																	echo '<option value="'.$hope_date_next_ym.'">'.substr($hope_date_next_ym,0,4).'年'.substr($hope_date_next_ym,4,2).'月'.'</option>';
																// hope_dateの次月から月額施術が開始
																} elseif($contract['start_ym'] == $hope_date_next_ym) {
																	echo '<option value="'.$hope_date_ym.'">'.substr($hope_date_ym,0,4).'年'.substr($hope_date_ym,4,2).'月'.'</option>';
																	echo '<option value="'. TA_Cook($contract['start_ym']).'" selected >'.TA_Cook(substr($contract['start_ym'],0,4).'年'.substr($contract['start_ym'],4,2).'月').'</option>';
																} else {
																	for($i= 0 ; $i< 2 ; $i++){
																		$start_ym = date("Ym",strtotime("first day of " .$data['hope_date'] . "+$i month"));
																		echo '<option value="'.$start_ym.'">'.substr($start_ym,0,4).'年'.substr($start_ym,4,2).'月'.'</option>';
																	}
																		echo '<option value="'. TA_Cook($contract['start_ym']).'" selected >'.TA_Cook(substr($contract['start_ym'],0,4).'年'.substr($contract['start_ym'],4,2).'月').'</option>';
																}
													} else { ?>
														  <option value="">-</option>  
														  <?php	
														  for($i= 0 ; $i< 2 ; $i++){
																$start_ym = date("Ym",strtotime("first day of " .$data['hope_date'] . "+$i month"));
																echo '<option value="'.$start_ym.'">'.substr($start_ym,0,4).'年'.substr($start_ym,4,2).'月'.'</option>';
															}
													} ?>										
												</select></td>
											</tr>
											<!--ローン不備ステータス時のデザイン変更-->
											<?php if($contract['loan_status']==5){?>
											<tr>
												<th valign="top" <?php if($contract['loan_status']==5)echo 'class="attention_item_o'; ?>">※ローン不備確認済:</th>
												<td><input type="checkbox" name="if_loan_deficiency" value="1" class="form-checkbox" /></td>
											</tr>
											<?php } else {?>
												<input type="hidden" name="if_loan_deficiency" value="0" class="form-checkbox" />
											<?php } ?>
											<tr>
												<th valign="top">オプション:</th>
												<?php if($_POST['free_flg']) { ?>
													<td><select name="option_name" class="styledselect_form_3" onChange="keisan()"><?php Reset_Select_Key($gFreeOption, $contract['option_name']);?></select></td>
												<?php } else { ?>
													<td><select name="option_name" class="styledselect_form_3" onChange="keisan()"><?php Reset_Select_Key( $gOption1 , $sales['option_name']);?></select></td>
												<?php } ?>
											</tr>
											<tr>
												<th valign="top">オプション金額:</th>
												<?php if($_POST['free_flg']) { ?>
													<td><input type="text" name="option_price" value="<?php echo TA_Cook($contract['option_name'] ? 0 : 0) ;?>" class="inp-form" readonly="readonly"/></td>
												<?php } else { ?>
													<td><input type="text" name="option_price" value="<?php echo TA_Cook($sales['option_price']) ;?>" class="inp-form" /></td>
												<?php } ?>
											</tr>
											<?php if($data['type']==1 || $data['type']==32 || $data['type']==33 || $_POST['free_flg']) { ?>
												<tr>
													<?php if($data['type']==1 || $data['type']==33) { ?>
														<th valign="top">カウンセリング担当:</th>
													<?php } elseif($data['type']==32) { ?>
														<th valign="top">ミドルカウンセリング担当:</th>
													<?php } elseif($_POST['free_flg']) { ?>
														<th valign="top">処理者:</th>
													<?php } ?>
													<td><select name="cstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$contract['contract_date']) , ($contract['staff_id'] ? $contract['staff_id'] : $data['cstaff_id']) ,getDatalist5("shop",$data['shop_id']));?></select></td>
												</tr>
											<?php } ?>

											<?php if(!$_POST['free_flg']) { ?>
												<tr>
													<th valign="top">レジ担当:</th>
													<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , $data['rstaff_id'],getDatalist5("shop",$data['shop_id']));?></select></td>
												</tr>

												<tr>
													<th valign="top">前受金非計上フラグ:</th>
													<!-- エラーをする前にチェックを入れていた場合、エラーの時に外れないようにする -->
													<td><input type="checkbox" name="non_record_flg" value="1" class="form-checkbox"
													<?php if($sales['non_record_flg'] == 1) {
															echo "checked";
													}
													?> /></td>
												</tr>
											<?php } ?>
											<tr>
												<th valign="top">備考:</th>
												<td><textarea name="memo" class="form-textarea2"><?php echo TA_Cook(($_POST['free_flg']) ? $contract['memo'] : $sales['memo']);?></textarea></td>
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
												<div class="title"><a href="../customer/edit.php?customer_id=<?php echo $customer['id']?>" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
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
													<div class="right"><h5>名前 : <?php echo $customer['name'] ? $customer['name'] : $customer['name_kana']?></h5></div>
													<div class="clear"></div>
													<!--<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>カウンセリング担当 : <?php echo $staff_list[$customer['staff_id']]?></h5></div>
													<div class="clear"></div>-->
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									<?php if($data['reg_flg']){?>

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
														<h5><a href="javascript:void(0);" onclick="window.open('../pdf/pdf_out0.php?sales_id=<?php echo $sales['id'];?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" title="領収書発行へ">領収書</a></h5>

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
				<td id="tbl-border-bottom"></td>
				<th class="sized bottomright"></th>
			</tr>
		</table>
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
	// 新月額のプルダウンに色を付ける
	function new_monthly(target_select){
		var $options,$options_num,$i;
		$options = $("[name='" + target_select + "']").find('option'),
		$options_num = $options.length;
		for($i =0; $i<$options_num; $i++){
			var $this = $options[$i];
			if($this.text.indexOf('新月額') !== -1){
				$this.classList.add('new_monthly');
			}
		}
	};
	// 月額のトリートメント開始年月の表示制御 2016/10/18 add by shimada
	function start_ym(target_id){
		// コースタイプを取得する
		var course_id = document.form1.course_id.value;
		var json_course_type = '<?php echo $json_course_type; ?>';
		var course_types = JSON.parse(json_course_type);
		var course_type = course_types[course_id]; // コースタイプ 0.パック、1.月額
		// 月額の時：トリートメント開始年月 ボックスを表示させる
		if (course_type==1){
			$(target_id).css("display","table-row");
		} else {
			$(target_id).css("display","none");
		}
	};
    new_monthly('course_id');
	start_ym('#start_ym');
</script>
<?php include_once("../include/footer.html");?>