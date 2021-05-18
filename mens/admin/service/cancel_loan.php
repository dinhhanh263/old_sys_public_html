<?php include_once("../library/service/cancel_loan.php");?>
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
function keisan3(){
	if(document.form1.keep.value>0){
		var payment = '<?php echo $data['payment'];?>';
		document.form1.change.value = Number(document.form1.keep.value) - Number(payment); // お釣りを表示
	}
}
</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>ローン取消処理<?php echo ($gMsg) ;?></h1></div>
			<div id="register-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<!-- start id-form -->
								<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
									<input type="hidden" name="action" value="edit" />
									<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" /> <!--店舗移動対応-->
									<input type="hidden" name="staff_id" value="<?php echo $data["staff_id"];?>" />
									<input type="hidden" name="pay_date" value="<?php echo $data["pay_date"];?>" />
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled><?php Reset_Select_Key( $gResType3 , 9);?></select></td>
											</tr>
											<tr>
												<th valign="top">契約番号:</th>
												<td><input type="text" name="pid" value="<?php echo $_GET['pid']; ?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">コース:</th>
												<td><select name="course_id" class="styledselect_form_3" onChange="keisan()" disabled><option>-</option><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select></td>
												<input type="hidden" name="course_id" value="<?php echo TA_Cook($data['course_id']) ;?>" />
											</tr>
											<tr>
												<th valign="top">コース金額合計（税込）:</th>
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($data['fixed_price']) ;?>" class="inp-form" disabled/></td>
												<input type="hidden" name="fixed_price" value="<?php echo TA_Cook($data['fixed_price']) ;?>" />
											</tr>
											<tr>
												<th valign="top">値引き:</th>
												<td><input type="text" name="discount" value="<?php echo TA_Cook($data['discount']) ;?>" id="fm2" class="inp-form" disabled /></td>
												<input type="hidden" name="discount" value="<?php echo TA_Cook($data['discount']) ;?>" />
											</tr>
											<tr>
												<th valign="top">商品金額合計（税込）:</th>
												<td><input type="text" name="price" value="<?php echo TA_Cook($data['price']) ;?>" class="inp-form" disabled /></td>
												<input type="hidden" name="price" value="<?php echo TA_Cook($data['price']) ;?>" />
											</tr>
											<tr>
												<th valign="top">既収金額:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['payment']-$data['payment_loan']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">ローン:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['payment_loan']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<!--<tr>
												<th valign="top">返金額(現金):</th>
												<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo (0-($data['payment']-$data['payment_loan']));?>" class="inp-form" /></td>

											</tr> 
											<tr>
												<th valign="top">返金額(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="" class="inp-form" /></td>
											</tr> 
											<tr>
												<th valign="top">返金額(振込):</th>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="" class="inp-form" /></td>
											</tr> -->
											<tr>
												<th valign="top">ローン取消金額:</th>
												<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo (0-$data['payment_loan']);?>" class="inp-form" /></td>
											</tr> 


											<tr>
												<th valign="top">レジ担当:</th>

												<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['rstaff_id'],getDatalist4("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">処理日:</th>
												<td><input type="tel" name="cancel_date" value="<?php echo $data['cancel_date']<>"0000-00-00" ? $data['cancel_date']  : date('Y-m-d') ;?>" class="inp-form"  /></td>
											</tr> 
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
												<tr><td colspan="2"><h1>ご明細</h1></td></tr>
												<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
												<tr>
													<td id="course_name" class="reg"><?php echo $course_list[$data['course_id']];?></td>
													<td id="course_price" class="regPrice">￥<?php echo number_format($data['fixed_price']);?></td>
												</tr>
												<tr>
													<td class="reg">値引き</td>
													<td id="discount" class="regPrice">▲￥<?php echo number_format($data['discount']);?></td>
												</tr>

												<tr>
													<td class="regTotal">商品金額</td>
													<td id="total" class="regTotalPrice">￥<?php echo number_format($data['price'] );?></td>
												</tr>
												<tr>
													<td class="regTotal">既収金額</td>
													<td id="payment" class="regTotalPrice">￥<?php echo number_format($data['payment']-$data['payment_loan']);?></td>
												</tr>
												<tr>
													<td class="regTotal">ローン取消金額</td>
													<td id="l_payment" class="regTotalPrice">￥<?php echo number_format($data['payment_loan']);?></td>
												</tr>
												<!--<tr>
													<td class="regTotal">返金額</td>
													<td id="payment" class="regTotalPrice">￥<?php echo number_format($data['payment']-$data['payment_loan']);?></td>
													<td id="payment" class="regTotalPrice">￥<?php echo number_format($data['payment']-$data['payment_loan']);?></td>
												</tr>-->
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
														
															<h5>領収書</h5>
														
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
					</div><!--  end content-table-inner  -->
			</div><!--  end register-table  -->
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>