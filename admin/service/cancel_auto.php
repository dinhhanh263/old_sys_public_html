<?php include_once("../library/service/cancel_auto.php");?>
<?php include_once("../include/header_menu.html");?>

<!-- 月のDATEPICKER を読み込むためのJS -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>
</form>

<script type="text/javascript">
function keisan2(){
	// 手数料
	var charge0 = document.form1.charge.value; 
	var charge = String( charge0 ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#charge').html("￥" + charge);   //手数料

	//返金額(手数料含)
	var remained_price0 = <?php echo $remained_price ;?> ;
	var payment0 = charge0 - remained_price0;
	var payment = String( payment0 ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment);   //手数料
}
function keisan3(){
	if(document.form1.keep.value>0){
		var return_price0 = <?php echo $return_price ;?> ;
		// 手数料
		var charge0 = document.form1.charge.value; 
		//返金額
		var remained_price0 = <?php echo $remained_price ;?> ;
		//返金額(手数料含)
		if(charge0==0){
			var return_price =　　Number(return_price0);
		}else{
			var return_price =　Number(charge0)-Number(remained_price0);
		}
		

		document.form1.change.value = Number(document.form1.keep.value) - Math.abs(return_price); // お釣りを表示
	}
}
</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>自動解約処理<?php echo ($gMsg) ;?></h1></div>
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
								<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
									<input type="hidden" name="action" value="edit" />
									<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" /><!--店舗移動対応-->
									<input type="hidden" name="staff_id" value="<?php echo $data["staff_id"];?>" />
									<input type="hidden" name="type" value="<?php echo $data["type"];?>" />
									<input type="hidden" name="pay_date" value="<?php echo $data["pay_date"];?>" />
									
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled ><?php Reset_Select_Key( $gResType3 , 12);?></select></td>
											
												<td rowspan="20">
													<div style="float:left;padding:40px;">													
														<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
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
																<td id="total" class="regTotalPrice">￥<?php echo number_format($data['fixed_price']-$data['discount'] );?></td>
															</tr>
															<tr>
																<td class="reg">支払済金額<!--<br>(ローン自動解消)--></td>
																<td class="regPrice">￥<?php echo number_format($payed_price);?></td>
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
																<td class="reg">残金<!--<br>(ローン自動解消)--></td>
																<td class="reg">￥<?php echo number_format(0-$remained_price);?></td>
																<!--<td class="regTotalPrice">￥<?php echo $sales['id'] ? number_format($sales['payment']-$charge) : number_format(0-$remained_price);?></td>-->
															<!--</tr>
															<tr>
																<td class="reg">手数料</td>
																<td id="charge" class="regPrice">￥<?php echo number_format($charge);?></td>
															</tr>
															<tr>
																<td class="regTotal">返金額(手数料含)</td>
																<td id="payment" class="regTotalPrice">￥<?php echo number_format(0-$return_price);?></td>
															</tr>
															<tr><td></td></tr>
															<tr>
																<td class="reg">お預かり</td>
																<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan3()"/></td>
															</tr>
															<tr>
																<td class="reg">お釣り</td>
																<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
															</tr>-->
														</table>
														<?php if($complete_flg){?>
															<div align="right"><a href="./">レジ一覧へ<a></div>
														<?php } ?>
													</div>

												<td>
													
											</tr>
											<tr>
												<th valign="top">コース:</th>
												<td><select name="course_id" class="styledselect_form_3" disabled><option>-</option><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select></td>
												<input type="hidden" name="course_id" value="<?php echo $data['course_id'] ;?>" />
											</tr>
											<tr>
												<th valign="top">コース金額（税込）:</th>
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($data['fixed_price']) ;?>" class="inp-form"  disabled/></td>
												<input type="hidden" name="fixed_price" value="<?php echo $data['fixed_price'] ;?>" />
											</tr>
											<tr>
												<th valign="top">値引き:</th>
												<td><input type="text" name="discount" value="<?php echo TA_Cook($data['discount']) ;?>" id="fm2" class="inp-form"  disabled/></td>
												<input type="hidden" name="discount" value="<?php echo $data['discount'] ;?>" />
											</tr>
											<tr>
												<th valign="top">商品金額（税込）:</th>
												<td><input type="text" value="<?php echo TA_Cook($data['fixed_price']-$data['discount']) ;?>" class="inp-form" disabled /></td>
												<input type="hidden" name="price" value="<?php echo $data['price'] ;?>" />
											</tr>
											
											<tr>
												<th valign="top">売掛金:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['balance']) ;?>" class="inp-form" disabled /></td>
												<input type="hidden" name="balance" value="<?php echo $data['balance'] ;?>" />
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
												<th valign="top">残金<!--(ローン自動解消)-->:</th>
												
												<td><input type="text" name="" value="<?php echo  (0-$remained_price) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr><td>========================</td><td>=============================</td></tr>
											<tr>
												<th valign="top">返金額(現金):</th>
												<td><input type="tel" name="payment_cash" value="<?php echo $sales['payment_cash'] ;?>" placeholder="無し" id="fm3" class="inp-form" /></td>
											</tr> 
											<tr>
												<th valign="top">返金額(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo $sales['payment_card'];?>" placeholder="無し" class="inp-form" /></td>
											</tr> 
											<tr>
												<th valign="top">返金額(振込):</th>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo $sales['payment_transfer'] ;?>" placeholder="<?php echo (0-$remained_price) ;?>" class="inp-form" /></td>
											</tr> 
											<tr><td>========================</td><td>=============================</td></tr>
											<!--
											<tr>
												<th valign="top">返金額:</th>
												<td><input type="tel"  value="なし" class="inp-form" disabled/></td>
											</tr>  -->

											<tr>
												<th valign="top">レジ担当:</th>
												<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , $data['rstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">解約日:</th>
												<td><input type="tel" name="cancel_date" value="<?php echo $data['cancel_date']<>"0000-00-00" ? $data['cancel_date']  : date('Y-m-d') ;?>" class="inp-form"  /></td>
											</tr> 
											<tr>
												<th valign="top">返金完了日:</th>
												<td><input  class="inp-form" name="terminate_day" type="text" id="day" value="<?php echo $data['terminate_day'];?>" autocomplete="off"/></td>
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
															<h5><a href="javascript:void(0);" onclick="window.open('../pdf/pdf_out.php', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
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
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>