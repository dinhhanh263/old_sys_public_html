<?php include_once("../library/service/cooling_off.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<script type="text/javascript">
function keisan(){
	var course_id = document.form1.course_id.selectedIndex ;
	var course_prices_str = '<?php echo $course_prices;?>';
	var course_prices = course_prices_str.split(',');        //文字列をカンマで分解し、配列化
	document.form1.fixed_price.value = course_prices[course_id]; // コース金額（税込）
	var discount = document.form1.discount.value ;
	var price = course_prices[course_id] - discount;
	document.form1.price.value = price; // 請求金額（税込）

	//明細--------------------------------------------------------------------------------
	var course_names_str = '<?php echo $course_names;?>';
	var course_names = course_names_str.split(',');        //文字列をカンマで分解し、配列化
	var course_name =  course_names[course_id];
	$('#course_name').html(course_name); 				  //コース名（明細）

	var course_price = String( course_prices[course_id] ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#course_price').html("￥" + course_price);         //コース金額（明細）

	var total = String( price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#total').html("￥" + total);         //合計：請求金額（明細）

	var discount2 = String( discount ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	if(discount > 0){
		$('#discount').html("▲￥" + discount2);         //値引き（明細）
	}

}
</script>
<script type="text/javascript">
function keisan2(){
	var payment = document.form1.payment.value; //既収金額

	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment2);
}
</script>
<script type="text/javascript">
function keisan3(){
	if(document.form1.keep.value>0){
		var payment = '<?php echo $contract_p['payment'];?>';
		document.form1.change.value = Number(document.form1.keep.value) - Number(payment); // お釣りを表示
	}
}
</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>クーリングオフ処理<?php echo ($gMsg) ;?></h1></div>
		<div id="register-table">
			<!--  start content-table-inner -->
			<div id="content-table-inner">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
										<td colspan="3">
											<div id="today_treatment">
												<!--  契約情報を表示する -->
												<?php foreach ($pid_contract as $key => $value): ?>
												<span class="c_course">
													<span class="course_cont1">
														契約番号:
													</span>
													<span class="course_cont2">
														<?php echo $value['pid'];?>
													</span>
													<span class="course_cont1">
														コース:
													</span>
													<span class="course_cont2">
														<input type="hidden" value="<?php echo $contract_p['course_id'];?>"><?php echo $course_list[$value['course_id']];?>
													</span>
													<!-- 契約部位あり -->
													<?php if($value['contract_part']){?>
													<span class="course_cont1">
														契約部位:
													</span>
													<span class="course_cont2">
														<?php $value['contract_part'] = explode(",", $value['contract_part']);?>
														<?php foreach ($value['contract_part'] as $key => $part): ?>
															<?php 
																echo $gContractParts[$part];
																if ($part <> end($value['contract_part'])) { echo ',';}
															?>
														<?php endforeach; ?>
													</span>
													<?php }?>
													<span class="course_cont1">
														コース金額:
													</span>
													<span class="course_cont2">
														<?php echo number_format($value['fixed_price']);?>
													</span>
												</span>
												<?php endforeach; ?>
											</div>											
										</td>
									</tr>
					<tr valign="top">
						<!-- start id-form -->
						<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="id" value="<?php echo $contract_p["id"];?>" />
							<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
							<input type="hidden" name="shop_id" value="<?php echo $contract_p["shop_id"];?>" /> <!--店舗移動対応?-->
							<input type="hidden" name="staff_id" value="<?php echo $contract_p["staff_id"];?>" />
							<input type="hidden" name="pay_date" value="<?php echo $contract_p["pay_date"];?>" />
							<input type="hidden" name="confirm_payment" value="<?php echo $contract_p["payment"];?>" />
							<td>
								<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
									<!-- <tr>
										<th valign="top">区分:</th>
										<td><select name="type" class="styledselect_form_3" disabled><?php Reset_Select_Key( $gResType3 , 4);?></select></td>
									</tr> -->
									<!-- <tr>
										<th valign="top">コース:</th>
										<td><select name="course_id" class="styledselect_form_3" onChange="keisan()" disabled><option>-</option><?php Reset_Select_Key_Group( $course_list , $contract_p['course_id'],$gCourseGroup);?></select></td>
										<input type="hidden" name="course_id" value="<?php echo TA_Cook($contract_p['course_id']) ;?>" />
									</tr> -->
									<tr>
										<th valign="top">コース金額合計（税込）:</th>
										<td><input type="text" name="fixed_price" value="<?php echo TA_Cook(number_format($contract_p['fixed_price'])) ;?>" class="inp-form" disabled/></td>
										<input type="hidden" name="fixed_price" value="<?php echo TA_Cook($contract_p['fixed_price']) ;?>" />
									</tr>
									<tr>
										<th valign="top">値引き合計:</th>
										<td><input type="text" name="discount" value="<?php echo TA_Cook(number_format($contract_p['discount'])) ;?>" id="fm2" class="inp-form" disabled /></td>
										<input type="hidden" name="discount" value="<?php echo TA_Cook($contract_p['discount']) ;?>" />
									</tr>
									<tr>
										<th valign="top">商品金額（税込）:</th>
										<td><input type="text" name="price" value="<?php echo TA_Cook(number_format($contract_p['price'])) ;?>" class="inp-form" disabled /></td>
										<input type="hidden" name="price" value="<?php echo TA_Cook($contract_p['price']) ;?>" />
									</tr>
									<tr>
										<th valign="top">既収金額:</th>
										<td><input type="text" name="payment" value="<?php echo TA_Cook($contract_p['payment']) ;?>" class="inp-form" onChange="keisan2()" /></td>
									</tr>

									<tr>
										<th valign="top">返金額(現金):</th>
										<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo $sales['payment_cash']?$sales['payment_cash']:''?>" class="inp-form" /></td>
									</tr>
									<tr>
										<th valign="top">返金額(カード):</th>
										<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo $sales['payment_card']?$sales['payment_card']:''?>" class="inp-form" /></td>
									</tr>
									<tr>
										<th valign="top">返金額(振込):</th>
										<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo $sales['payment_transfer']?$sales['payment_transfer']:(0-$contract_p['payment']);?>" class="inp-form" placeholder="<?php echo (0-$contract_p['payment']);?>"/></td>
									</tr>
									<tr>
										<th valign="top">返金額(ローン):</th>
										<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo $sales['payment_loan']?$sales['payment_loan']:''?>" class="inp-form" /></td>
									</tr>

									<tr>
										<th valign="top">レジ担当:</th>

										<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $sales['rstaff_id']?$sales['rstaff_id']:$contract_p['rstaff_id'],getDatalist4("shop",$_POST['shop_id']));?></select></td>
									</tr>
									<tr>
										<th valign="top">処理日:</th>
										<td><input type="tel" name="cancel_date" value="<?php echo $contract_p['cancel_date']<>"0000-00-00" ? $contract_p['cancel_date']  : date('Y-m-d') ;?>" class="inp-form"  /></td>
									</tr>
									<tr>
										<th valign="top">備考:</th>
										<td><textarea name="memo" class="form-textarea2"><?php echo TA_Cook($contract_p['memo']) ;?></textarea></td>
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
										<tr><td colspan="2"><h1>ご明細</h1></td></tr>
										<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[0]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[0]);?></td>
										</tr>
									<?php if($course_id[1]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[1]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[1]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[2]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[2]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[2]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[3]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[3]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[3]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[4]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[4]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[4]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[5]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[5]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[5]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[6]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[6]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[6]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[7]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[7]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[7]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[8]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[8]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[8]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[9]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[9]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[9]);?></td>
										</tr>
									<?php } ?>
									<?php if($course_id[10]){?>
										<tr>
											<td class="reg"><?php echo $course_list[$course_id[10]];?></td>
											<td class="regPrice">￥<?php echo number_format($fixed_price[10]);?></td>
										</tr>
									<?php } ?>
										<tr>
											<td class="reg">値引き合計</td>
											<td id="discount" class="regPrice">▲￥<?php echo number_format($contract_p['discount']);?></td>
										</tr>

										<tr>
											<td class="regTotal">商品金額（値引後合計）</td>
											<td id="total" class="regTotalPrice">￥<?php echo number_format($contract_p['price'] );?></td>
										</tr>
										<tr>
											<td class="regTotal">既収金額</td>
											<td id="payment" class="regTotalPrice">￥<?php echo number_format($contract_p['payment']);?></td>
										</tr>
										<tr>
											<td class="regTotal">返金額</td>
											<td id="payment" class="regTotalPrice">￥<?php echo $sales['payment']?number_format($sales['payment']):number_format($contract_p['payment']);?></td>
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
												<?php if($sales['id']){?>
													<h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_cooling_off.php<?php echo $mpdf_cooling_off;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
												<?php }else{?>
													<h5>領収書</h5>
												<?php };?>
											</div>
											<div class="clear"></div>
										</div><!-- end related-act-inner -->
										<div class="clear"></div>
									</div><!-- end related-act-bottom -->
								</div><!-- end related-activities -->
							</td>
						</form><!-- end id-form  -->
					</tr>
				</table>
					<div class="clear"></div>
			</div><!--  end content-table-inner  -->
		</div>
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>