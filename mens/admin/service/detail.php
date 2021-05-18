<?php include_once("../library/service/detail.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<?php if($sales['option_name'] == 4){?><!-- 月額支払時 -->
<style type="text/css">
	#option_year{ display:block;}
	#option_month{ display:block;}
	#option_month2{ display:block;}
</style>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){
	if(document.getElementById("g_error") != null){
	document.form1.option_name.options[3].selected = true;
	statement();
	}
});

// function keisan3(){
// 	if(document.form1.keep.value>0){
// 		document.form1.change.value = document.form1.keep.value - document.form1.payment_cash.value - document.form1.payment_card.value - document.form1.payment_transfer.value - document.form1.payment_loan.value - document.form1.option_price.value; // お釣りを表示
// 	}
// }
</script>

<script type="text/javascript">
function optionerror(){
	alert('3ヶ月よりも前の日付が入力されています。確認してください。');
}

</script>

<!--全角英数字、ハイフン->半角-->
<script type="text/javascript">
$(function() {
  $('.fm2').change(function(){
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
		<div id="page-heading">
			<h1>レジ精算
				<span class="name"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?>様</span>
			</h1>
			<?php if($gMsg){ ?>
				<div class="warning">
					<?php echo ($gMsg) ;?>
				</div>
			<?php } ?>
		</div>
			<!--  start register-table -->
			<div id="register-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
						<!-- start id-form -->
						<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return <?php echo $reservation['type']==7?"conf_receivable()":"conf_detail('')";?>;">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
							<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
							<input type="hidden" name="contract_id" value="<?php echo $contract["id"];?>" />
							<input type="hidden" name="times" value="<?php echo $contract["times"];?>" />
							<!-- <input type="hidden" name="r_times" value="<?php echo $contract["r_times"];?>" /> -->
							<input type="hidden" name="course_id" value="<?php echo $contract["course_id"];?>" />
							<input type="hidden" name="course_type" value="<?php echo $course["type"];?>" />
							<input type="hidden" name="fixed_price" value="<?php echo $contract["fixed_price"];?>" />
							<input type="hidden" name="discount" value="<?php echo $contract["discount"];?>" />
							<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" />
							<input type="hidden" name="type" value="<?php echo $data["rsv_status"] ? $data["rsv_status"] : $data["type"];?>" />
							<input type="hidden" name="pay_date" value="<?php echo $data["hope_date"];?>" />
							<div id="today_type">
								区分:<select class="form_fixed" disabled><?php Reset_Select_Key( $gResType3 , ($data["rsv_status"] ? $data["rsv_status"] : $data["type"]));?></select>
							</div>
							<div id="today_treatment">
								<?php foreach ($select_contract as $key => $contract_data): ?>
								<!-- 消化テーブルのデータ参照 -->
								<?php // 契約状態・消化回数過去分情報取得
									$r_times_data = Get_Table_Row("r_times_history"," WHERE del_flg=0 and (pay_date <= '".addslashes($reservation['hope_date'])."') and contract_id = '".addslashes($contract_data['id'])."' ORDER BY pay_date DESC, id DESC");
									if(1000<$contract_data['course_id'])$r_times_contract_data = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id ='".addslashes($contract_data['customer_id'])."' and course_id= '".addslashes($contract_data['old_course_id'])."'");//PREMIUM(返金保証回数終了コース)

									// 消化データの表示(消化テーブルにデータがないとき、初期表示をする)
									if(!$r_times_data || is_null($r_times_data)){
										// PREMIUM(返金保証回数終了コース)以外のコースは初期に0をセット
										// PREMIUM(返金保証回数終了コース)は旧コースの消化回数を表示
										if($contract_data['course_id']<=1000){
										$r_times_data['r_times'] =0; // 消化回数なし 
											$r_times = $r_times_data['r_times'].'/'.$contract_data['times'];
										} else {
											$r_times =$r_times_contract_data['r_times']; // 旧コースの消化回数(契約テーブルのデータ)
										}
									} else {
										// PREMIUM(返金保証回数終了コース)以外のコースは分数で消化回数を表示
										// PREMIUM(返金保証回数終了コース)は消化回数のみ表示
										if($contract_data['course_id']<=1000){
											$r_times = $r_times_data['r_times'].'/'.$contract_data['times'];
										} else {
											$r_times = $r_times_data['r_times'];
										}
									}
								?>
								<span class="c_course">
									<span class="course_cont1">
										契約番号:
									</span>
									<span class="course_cont2">
										<?php echo $contract_data['pid'];?>
									</span>
									<span class="course_cont1">
										コース:
									</span>
									<span class="course_cont2">
										<input type="hidden" value="<?php echo $data['course_id'];?>"><?php echo $course_list[$contract_data['course_id']];?>
									</span>
									<span class="course_cont1">
										消化回数:
									</span>
									<span class="course_cont2">
										<?php echo $r_times; ?>回
									</span>
								</span>
								<?php endforeach; ?>
							</div>
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<tr valign="top">
										<td>
											<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<!--  トリートメント OR トリートメント/売掛回収 消化ボタン表示 -->
											<?php if($reservation['type'] ==2 || $reservation['type'] ==27){?>
												<tr>
													<th>役務消化:</th>
													<td>
														<input type="checkbox" name="if_service1" value="1" class="form-checkbox" <?php if($r_times_flg) echo "checked disabled"?> />
													</td>
												</tr>
											<?php } ?>
											<!--  売掛回収時 OR トリートメント/売掛回収 売掛情報を表示する -->
											<?php if($reservation['type'] ==7 || $reservation['type'] ==27){?>
												<tr>
													<th valign="top">コース金額（税込）合計:</th>
													<td><input type="tel" name="fixed_price" value="<?php echo number_format($contract_p['fixed_price']) ;?>" class="inp-form" disabled /></td>
												</tr>
												<tr>
													<th valign="top">値引き合計:</th>
													<td><input type="tel" name="discount" value="<?php echo number_format($contract_p['discount']) ;?>"  class="inp-form" disabled /></td>
												</tr>
												<?php //if($contract['balance']){ ?>
												<tr>
													<th valign="top">売掛金合計:</th>
													<td><input type="tel" name="price" value="<?php echo ($sales['id'] ? $sales['price'] : $contract_p['balance']) ;?>" class="inp-form"  onChange="remaining()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(現金):</th>
													<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo TA_Cook($sales['payment_cash']) ;?>" class="fm2 inp-form"  onChange="statement()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(カード):</th>
													<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo TA_Cook($sales['payment_card']) ;?>" class="fm2 inp-form"  onChange="statement()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(振込):</th>
													<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form" onChange="statement()" /></td>
												</tr>
												<tr>
													<th valign="top">残金支払(ローン):</th>
													<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($sales['payment_loan']) ;?>" class="inp-form" onChange="statement()" /></td>
												</tr>
												<?php //} ?>
											<?php } ?>
												<tr>
													<th valign="top">オプション:</th>
													<td><select name="option_name" class="styledselect_form_3" onChange="statement_option()"><?php Reset_Select_Key( $gOption , $option_name);?></select></td>
												</tr>

												<!-- <tr id="option_year">
													<th valign="top">何年支払代金:</th> -->
													<!--  年が選ばれていない場合は今年の年のプルダウンをセットする -->
													<?php /* $option_year = ($_POST['option_year'] == "" && $sales['option_year'] =="") ? date("Y") : $_POST['option_year'];*/ ?>
													<?php /*$option_year = ($option_year <> "") ? $option_year : TA_Cook($sales['option_year']);*/ ?>
													<!-- <td><select name="option_year" class="styledselect_form_3" onChange="statement_option()" ><?php /*Reset_Select_Val( $gOptionYear , $option_year);*/ ?></select></td>
												</tr> -->
												<!-- <tr id="option_month" >
													<th valign="top">何月分支払代金:</th>
													<td><input type="text" name="option_month" value="<?php echo $_POST['option_month']=($_POST['option_month']<>null) ? $_POST['option_month']: TA_Cook($sales['option_month']) ;?>" class="inp-form" onChange="statement_option()" placeholder="<?php echo date("n").','; echo date("n") =='12' ? '1': date("n")+1 ;?>" /></td>
												</tr> -->
												<!-- <tr id="option_month2" >
													<th valign="top">振替日:</th>
													<td><input type="text" name="option_date" value="<?php echo $sales['option_date']=="0000-00-00" ? $data['hope_date'] : (TA_Cook($sales['option_date'])) ;?>" class="inp-form" id="fm" placeholder="<?php echo date("Y-m-d");?>"/></td>
												</tr> -->

												<!-- シェービング代変更 2017/03/30～料金が変わるため処理分けを実施 2017/03/27 add by shimada-->
												<?php if($reservation['hope_date'] <= '2017-03-29'){ ?>
												<tr>
													<th valign="top">オプション支払(現金):</th>
													<td><input type="text" name="option_price" value="<?php echo TA_Cook($option_price_sum) ;?>" class="inp-form" onChange="statement()" /></td>
												</tr>
												<?php } else { ?>
												<tr>
													<th valign="top">オプション支払(現金):</th>
													<td><select name="option_price" class="styledselect_form_3" onChange="statement()"><?php Reset_Select_Val( $gShavingPrice , TA_Cook($option_price_sum) );?></select></td>
												</tr>
												<?php } ?>
												<!-- <tr>
													<th valign="top">オプション支払(振込):</th>
													<td><input type="text" name="option_transfer" value="<?php echo TA_Cook($option_transfer_sum) ;?>" class="inp-form" onChange="statement()" /></td>
												</tr> -->
												<!-- オプション支払 使わないためコメントアウト -->
												<input type="hidden" name="option_transfer" value="0">

												<!-- シェービング代変更 2017/03/30～料金が変わるため処理分けを実施 2017/03/27 add by shimada-->
												<?php if($reservation['hope_date'] <= '2017-03-29'){ ?>
												<tr>
													<th valign="top">オプション支払(カード):</th>
													<td><input type="text" name="option_card" value="<?php echo TA_Cook($option_card_sum) ;?>" class="inp-form" onChange="statement()" /></td>
												</tr>
												<?php } else { ?>
												<tr>
													<th valign="top">オプション支払(カード):</th>
													<td><select name="option_card" class="styledselect_form_3" onChange="statement()"><?php Reset_Select_Val( $gShavingPrice , TA_Cook($option_card_sum) );?></select></td>
												</tr>
												<?php } ?>
											<?php if($course['type']){?>
												<tr>
													<th valign="top">施術部位:</th>
													<td><select name="part" class="styledselect_form_3"><?php Reset_Select_Key( $gPart , $_POST['part'] ? $_POST['part']  : $reservation['part']);?></select></td>
												</tr>
											<?php } ?>

												<?php if($data['type']==2 || $reservation['type'] ==27){ ?>
												<tr>
													<th valign="top">施術主担当:</th>
													<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>

												</tr>
												<tr>
													<th valign="top">施術サブ担当1:</th>
													<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub1_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
												<tr>
													<th valign="top">施術サブ担当2:</th>
													<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub2_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>

												<?php }?>


												<tr>
													<th valign="top">レジ担当:</th>
													<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['rstaff_id'],getDatalist4("shop",$data['shop_id']));?></select></td>
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
										<td class="ditail_middle">
												<div>
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
														<?php if($discount_sum){?>
															<tr>
																<td class="reg">値引き（合計）</td>
																<td class="regPrice">▲￥<?php echo number_format($discount_sum);?></td>
															</tr>
															<tr class="hr2">
																<td class="reg">コース金額（値引後合計）</td>
																<td class="regPrice">￥<?php echo number_format($fixed_price_sum - $discount_sum);?></td>
															</tr>
															<tr>
																<td class="regTotal">売掛金</td>
																<td class="regTotalPrice">￥<span id="remaining"><?php if(!$sales['price']){echo"なし";}else{echo number_format($sales['price']);}; ?></span></td>
															</tr>
														<?php } ?>
														<?php if($reservation['type'] ==7 || $reservation['type'] ==27){?>
															<tr class="hr">
																<td class="reg">残金支払</td>
																<td class="regPrice">￥<span id="payment"><?php echo number_format($sales['payment']);?></span></td>
															</tr>
														<?php } ?>
														<?php if($reservation['type'] ==7 || $reservation['type'] ==27){?>
															<tr>
														<?php }else{ ?>
															<tr class="hr">
														<?php } ?>
																<td class="reg"><span id="option_name"><?php echo $option_name ? $gOption[$option_name] : "オプション";?></span>代支払</td>
																<!-- <td class="regPrice">￥<span id="option_price"><?php echo number_format($option_price_sum);?></span></td> -->
																<td class="regPrice">￥<span id="option_price"><?php echo number_format($option_card_sum+$option_price_sum);?></span></td>
															</tr>
															<tr class="hr">
																<td class="regTotal">支払合計</td>
																<td class="regTotalPrice">￥<span id="total"><?php echo number_format($sales['payment'] + $sales['option_price'] + $sales['option_transfer'] + $sales['option_card']);?></span></td>
															</tr>
														<?php if($reservation['type'] ==7 || $reservation['type'] ==27){?>
															<tr class="warning">
														<?php }else{ ?>
															<tr>
														<?php } ?>
																<td class="reg font11">支払後売掛残金</td>
																<td class="regPrice">￥<span id="balance"><?php echo number_format($sales['balance']);?></span></td>
															</tr>
															<!-- <tr class="hr">
																<td class="reg">お預かり</td>
																<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan3()"/></td>
															</tr>
															<tr>
																<td class="reg">お釣り</td>
																<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
															</tr> -->
														</table>
												</div>
										</td>
										<td class="ditail_middle">
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
													<ul id="related-act-inner">
														<li class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></li>
														<li class="right"><h5>名前 : <?php echo $customer['name']?></h5></li>
														<li class="right"><h5>カウンセリング担当 : <?php echo $staff_list[$contract['staff_id']]?></h5></li>
													</ul><!-- end related-act-inner -->
												</div><!-- end related-act-bottom -->
											</div><!-- end related-activities -->
										<!--  レジ清算済みのときのみ表示 -->
										<?php if($reservation['reg_flg']==1){?>
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
															<!--  トリートメント -->
															<?php if($reservation['type'] ==2){?>
															<h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_detail_treatment.php<?php echo $mpdf_detail;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
															<!--  売掛回収 -->
															<?php } elseif($reservation['type'] ==7 ){ ?>
															<h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_detail_accounts_receivable.php<?php echo $mpdf_detail;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
															<!--  トリートメント/売掛回収 -->
															<?php } elseif($reservation['type'] ==27){ ?>
															<h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_detail_treatment_and_accounts.php<?php echo $mpdf_detail;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#393939'" onmouseout="this.style.color='#94b52c'" title="領収書発行へ">領収書</a></h5>
															<?php } ?>
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
			</div><!--  end register-table  -->
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<script type="text/javascript" charset="utf-8"> /*  add201601  */
	var option =  JSON.parse('<?php echo json_encode($gOption); ?>');
	var option_num = document.form1.option_name.selectedIndex;
	var option_name = document.form1.option_name.options[option_num].value;
	var point = '<?php echo $point;?>';
  var tax2 = '<?php echo $tax2;?>';
</script>

<!-- シェービング代変更 2017/03/30～料金が変わるためJSを読み込ませない 2017/03/27 add by shimada-->
<?php if($reservation['hope_date'] <= '2017-03-29'){ ?>
<script src="../js/detail.js" type="text/javascript" charset="utf-8"></script>
<?php }?>


<script src="../js/jquery.guidance.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/jquery.guidance.css">
<!-- 予約チェックがなかった場合、エラー表示する -->
<script type="text/javascript">
	$(document).ready(function(){
	<?php if($chk_flg==true){ ?>
	    $("body").guidance({
	      guidanceAll :"前の画面に戻ってください。<br><?php echo $gResType3[$data['type']]?>を行うコースの「予約する」をチェックしてください。"
	    });
	<?php } ?>
	});
</script>


<?php include_once("../include/footer.html");?>