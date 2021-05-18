<?php include_once("../library/service/change.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<script type="text/javascript">
function keisan_change(){
	var course_id = document.form1.course_id.selectedIndex ; 
	var course_prices_str = '<?php echo $course_prices;?>';
	var course_prices = course_prices_str.split(',');        											// 文字列をカンマで分解し、配列化
	document.form1.fixed_price.value = course_prices[course_id]; 										// コース金額（税込）
    var remained_price = '<?php echo $remained_price;?>';
    var discount = document.form1.discount.value;
    var price = course_prices[course_id] - remained_price - discount ;									// 請求金額（税込）=コース金額-消化済金額
	document.form1.price.value = price; 	
	//var price = document.form1.price.value; 															// 請求金額（税込）

	var f_payment = document.form1.payment_cash.value ; 												// 初回入金(現金)
	var c_payment = document.form1.payment_card.value ; 												// 初回入金(カード)
	var t_payment = document.form1.payment_transfer.value ;												// 初回入金(振込)
	var l_payment = document.form1.payment_loan.value ; 												// 初回入金(ローン)

	var payment = Number(f_payment) + Number(c_payment) + Number(t_payment) + Number(l_payment);

	//明細--------------------------------------------------------------------------------
	var course_names_str = '<?php echo $course_names;?>';
	var course_names = course_names_str.split(',');        												// 文字列をカンマで分解し、配列化
	var course_name =  "新：" + course_names[course_id];
	$('#course_name').html(course_name); 				  												// コース名（明細）

	var course_price = String( course_prices[course_id] ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#course_price').html("￥" + course_price);         												// コース金額（明細）

	var price2 = String( price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); 							// 1,234
	$('#price').html("￥" + price2);         															// 入金金額（明細）

	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); 						// 1,234
	$('#payment').html("￥" + payment2);         														// 入金金額（明細）

	var balance = price - payment ;
	var balance2 = String( balance ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );						// 1,234
	$('#balance').html("￥" + balance2);         														// 入金金額（明細）
	if(f_payment > 0){
		keisan3();
	}
}

function keisan3(){
	// if(document.form1.keep.value>0){
		var payment_cash,total_payment,change;
		payment_cash = Number(document.form1.payment_cash.value);
		total_payment = Number(document.form1.payment_card.value + document.form1.payment_transfer.value + document.form1.payment_loan.value);
		document.form1.keep.value = payment_cash;
		change = (total_payment + payment_cash) - Number(document.form1.price.value)
		if(change > 0){
			document.form1.change.value = change; // お釣りを表示
		}else{
			document.form1.change.value = 0;
	}
	// }
}
</script>

<script type="text/javascript">
  // imobile_adv_sid = "6121";
  // imobile_adv_cq = "top=1";
  // document.write(unescape("<script src="" + ((document.location.protocol == "http:") ? "http" : "https") + "://spcnv.i-mobile.co.jp/script/adv.js?20120316"" + " type="text/javascript"></script>"));
</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>プラン変更処理<?php echo ($gMsg) ;?></h1></div>
		<div id="register-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
								<!-- start id-form -->
								<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
									<input type="hidden" name="action" value="edit" />
									<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $_POST["shop_id"];?>" /><!--店舗移動対応-->
									<input type="hidden" name="type" value="<?php echo $data["type"];?>" />
									<!--<input type="hidden" name="pay_date" value="<?php echo $data["pay_date"];?>" />-->
							<div id="today_type">
								区分:<select class="form_fixed" disabled><?php Reset_Select_Key( $gResType3 , 6);?></select>
							</div>
							<div id="today_treatment">
								<div class="old_course">
									<span class="old_cont1">
										<dl class="old_list">
											<dt>旧コース:</dt>
											<dd>
												<select name="" class="form_fixed" disabled><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select>
											</dd>
											<?php if($old_contract_parts){?>
											<dt>(選択部位):</dt>
											<dd>
												<?php echo $old_contract_parts;?>										
											</dd>
											<?php } ?>
											<dt>旧コース金額（税込）:</dt>
											<dd><?php echo number_format($data['fixed_price']);?>円</dd>
											<dt>値引き（旧）:</dt>
											<dd><?php echo number_format($data['discount']);?>円</dd>
											<dt>旧商品金額（税込）:</dt>
											<dd><?php echo number_format($data['fixed_price']-$data['discount']) ;?>円</dd>
											<dt>支払済金額:</dt>
											<dd><?php echo number_format($payed_price) ;?>円</dd>
										</dl>
									</span>
									<span class="old_cont2">
										<dl class="old_list">
											<dt>売掛金:</dt>
											<dd><?php echo number_format($data['balance']) ;?>円</dd>
											<dt>消化回数:</dt>
											<dd><?php echo number_format($data['r_times']) ;?>回</dd>
											<dt>消化単価:</dt>
											<dd><?php echo number_format($per_price) ;?>円</dd>
											<dt>消化金額:</dt>
											<dd><?php echo number_format($usered_price) ;?>円</dd>
											<dt>未消化金額:</dt>
											<dd><?php echo number_format($remained_price) ;?>円</dd>
										</dl>
									</span>
								</div>
							</div>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr valign="top">
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<!-- <tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled ><?php Reset_Select_Key( $gResType3 , 6);?></select></td>
											</tr>
											<tr>
												<th valign="top">旧コース:</th>
												<td><select name="" class="styledselect_form_3" disabled><option>-</option><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select></td>
											</tr>
											<tr>
												<th valign="top">旧コース金額（税込）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['fixed_price']) ;?>" class="inp-form"  disabled/></td>
											</tr>
											<tr>
												<th valign="top">値引き（旧）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['discount']);?>" id="fm2" class="inp-form"  disabled/></td>
											</tr>
											<tr>
												<th valign="top">旧商品金額（税込）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['fixed_price']-$data['discount']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">支払済金額:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($payed_price) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">売掛金:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['balance']) ;?>" class="inp-form" disabled /></td>
											</tr>

											<tr>
												<th valign="top">消化回数:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['r_times']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">消化単価:</th>
												<td><input type="text" name="" value="<?php echo $per_price ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">消化金額:</th>
												<td><input type="text" name="" value="<?php echo $usered_price ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">未消化金額:</th>
												<td><input type="text" name="" value="<?php echo $remained_price ;?>" class="inp-form" disabled /></td>
											</tr> -->

											<tr>
												<th valign="top">新コース:</th>
												<td><select name="course_id" class="styledselect_form_3" onChange="keisan_change()"><option>-</option><?php Reset_Select_Key_Group( $course_list , $new_contract['course_id'],$gCourseGroup);?></select></td>
											</tr>
											<tr><!-- 施術箇所選択 -->
												<th></th>
												<td class="single_inner  <?php echo $new_contract_parts_key ? '' : 'parts_area'?>">
													<?php echo InputCheckboxTagKey("contract_part",$gContractParts,$new_contract_parts_key,"")?>
												</td>
											</tr>
											<tr>
												<th valign="top">新コース金額（税込）:</th>
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($new_contract['fixed_price']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">値引き（新）:</th>
												<td><input type="text" name="discount" value="<?php echo TA_Cook($new_contract['discount']) ;?>" id="fm2" class="inp-form"  onChange="keisan_change()"　/></td>
											</tr>
											<tr>
												<th valign="top">請求金額:</th>
												<td><input type="text" name="price" value="<?php echo TA_Cook($new_contract['price']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">入金金額(現金):</th>
												<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo TA_Cook($new_contract['payment_cash']) ;?>" class="inp-form" onChange="keisan_change()" /></td>
											</tr> 
											<tr>
												<th valign="top">入金金額(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo TA_Cook($new_contract['payment_card']) ;?>" class="inp-form" onChange="keisan_change()" /></td>
											</tr> 
											<tr>
												<th valign="top">入金金額(振込):</th>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($new_contract['payment_transfer']) ;?>" class="inp-form" onChange="keisan_change()" /></td>
											</tr> 
											<tr>
												<th valign="top">入金金額(ローン):</th>
												<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($new_contract['payment_loan']) ;?>" class="inp-form" onChange="keisan_change()" /></td>
											</tr> 
											<tr>
												<th valign="top">ミドルカウンセリング担当:</th>
					
												<td><select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $new_contract['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">レジ担当:</th>
					
												<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $new_contract['rstaff_id'],getDatalist4("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">処理日:</th>
												<td><input type="tel" name="cancel_date" value="<?php echo $data['cancel_date']<>"0000-00-00" ? $data['cancel_date']  : date('Y-m-d') ;?>" class="inp-form"  /></td>
											</tr>
											<!-- 20160623メンズでは使っていないため非表示にする -->
											<!-- <tr>
												<th valign="top">新プラン適応日:</th>
												<td valign="bottom">
													<input type="radio" name="if_cancel_date" value="0" <?php echo !$data['if_cancel_date'] ? "checked" : "";?> class="form-radio" />翌日
													<input type="radio" name="if_cancel_date" value="1" <?php echo $data['if_cancel_date'] ? "checked" : "";?> class="form-radio" />当日
												</td>
											</tr>   -->
											<tr>
												<th valign="top">備考:</th>
												<td><textarea name="memo" class="form-textarea2"><?php echo TA_Cook($data['memo']) ;?></textarea></td>
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
								<div class="ditail_middle">
									<table id="expenditures">
										<tr><td colspan="2"><h1>プラン変更処理明細</h1></td></tr>
										<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
										<tr>
											<td class="reg">旧：<?php echo $course_list[$data['course_id']];?></td>
											<td class="regPrice">￥<?php echo number_format($data['fixed_price']);?></td>
										</tr>
										<tr>
													<td class="reg">値引き（旧）:</td>
													<td class="regPrice">▲￥<?php echo number_format($data['discount']);?></td>
										</tr>
												<tr>
											<td class="regTotal">商品金額（旧）</td>
													<td class="regTotalPrice">￥<?php echo number_format($data['fixed_price']-$data['discount']);?></td>
												</tr>
										<tr>
											<td class="reg">支払済金額</td>
											<td i class="regPrice">￥<?php echo number_format($payed_price);?></td>
										</tr>
										<tr>
											<td class="reg">消化回数</td>
											<td class="regPrice"><?php echo $data['r_times'];?></td>
										</tr>
										<tr>
											<td class="reg">消化単価</td>
											<td class="regPrice">￥<?php echo number_format($per_price);?></td>
										</tr>
										<tr>
											<td class="reg">消化金額</td>
											<td class="regPrice">￥<?php echo number_format($usered_price);?></td>
										</tr>
										<tr>
											<td class="regTotal">未消化金額</td>
											<td class="regTotalPrice">￥<?php echo number_format($remained_price);?></td>
										</tr>
										<tr>
											<td id="course_name" class="reg">新：<?php echo $course_list[$data['new_course_id']];?></td>
											<td id="course_price" class="regPrice">￥<?php echo number_format($new_contract['fixed_price']);?></td>
										</tr>
										<tr>
											<td class="regTotal">請求金額</td>
											<td id="price" class="regTotalPrice">￥<?php echo number_format($new_contract['price'] );?></td>
										</tr>
										<tr>
											<td class="regTotal">入金金額</td>
											<td id="payment" class="regTotalPrice">￥<?php echo number_format($new_contract['payment'] );?></td>
										</tr>
										<tr>
											<td class="reg">売掛金</td>
											<td id="balance" class="regPrice">￥<?php echo number_format($new_contract['balance'] );?></td>
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
										<div align="right"><a href="./">レジ一覧へ<a></div>
									<?php } ?>
								</div>
							</td>
							<td>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title"><a href="../customer/edit.php?id=<?php echo $customer['id']?>"  style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94b52c'" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
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
													<!--<div class="lines-dotted-short"></div>
												
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>カウンセリング担当 : <?php echo $staff_list[$customer['staff_id']]?></h5></div>
													<div class="clear"></div>-->
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									<?php if($receipt_flg){ ?>
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
														<h5>
													<a href="javascript:void(0);" onclick="window.open('../../mpdf_change.php<?php echo $mpdf_change;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a>
														</h5>
													</div>
													<div class="clear"></div>
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									<?php } ?>
									</td>
							</tr>
						</table>
						</form><!-- end id-form  -->
					</div><!--  end content-table-inner  -->
		</div>
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<script src="../js/detail.js" type="text/javascript" charset="utf-8"></script>
<?php include_once("../include/footer.html");?>
