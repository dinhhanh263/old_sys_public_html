<?php include_once("../library/account/reg_detail.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
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
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>レジ精算<?php echo $gMsg;?></h1></div>
			<!--  start register-table -->
			<div id="register-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
						<!-- start id-form -->
						<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
							<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
							<input type="hidden" name="shop_id" value="<?php echo $data["shop_id"];?>" /><!--契約店舗-->
							<input type="hidden" name="type" value="<?php echo $data["type"];?>" />
							<input type="hidden" name="pay_date" value="<?php echo $sales["pay_date"];?>" />
							<div id="today_type" class="today_type">
								区分:<select name="type" class="form_fixed" disabled><?php Reset_Select_Key( $gResType4 , $data['type']);?></select>
							</div>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr valign="top">
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<!-- <tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled><?php Reset_Select_Key( $gResType4 , $data['type']);?></select></td>
											</tr> -->
											<tr>
												<th valign="top">コース:</th>
												<td><select name="course_id" class="styledselect_form_3 guidance" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option>-</option><?php Reset_Select_Key_Group( $course_list , ($course_id[0] ? $course_id[0] : ($data['hp_flg'] ? 70 : 0)),$gCourseGroup);?></select></td>
											</tr>
											<tr><!-- 施術箇所選択 -->
												<th></th>
												<td class="single_inner  <?php echo ($contract_part[0]) ? '' : 'parts_area'?>">
													<?php echo InputCheckboxTagKey("contract_parts1",$gContractParts,$contract_part[0],"")?>
												</td>
											</tr>
											<tr>
												<th valign="top">コース金額（税込）:</th>
												<!--<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($sales['fixed_price']) ;?>" class="inp-form" <?php if($authority_level>6) echo "disabled"?>/></td>-->
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($fixed_price[0] ? $fixed_price[0] : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" /></td>
											</tr>
											<tr class="pre_line">
												<th valign="top">値引き1:</th>
												<td>
													<input type="text" name="discount" value="<?php echo $discount[0];?>" class="inp-form" onChange="reduce()">
												</td>
											</tr>
											<!--Mnes複数コース選択用-->
											<tr>
												<th valign="top">コース2:</th>
												<td><select name="course_id2" class="styledselect_form_3 guidance" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option>-</option><?php Reset_Select_Key_Group( $course_list , ($course_id[1] ? $course_id[1] : ($data['hp_flg'] ? 70 : 0)),$gCourseGroup);?></select></td>
											</tr>
											<tr><!-- 施術箇所選択 -->
												<th></th>
												<td class="single_inner <?php echo ($contract_part[1]) ? '' : 'parts_area'?>">
													<?php echo InputCheckboxTagKey("contract_parts2",$gContractParts,$contract_part[1],"")?>
												</td>
											</tr>
											<tr>
												<th valign="top">コース2金額（税込）:</th>
												<td><input type="text" name="fixed_price2" value="<?php echo TA_Cook($fixed_price[1] ? $fixed_price[1] : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" /></td>
											</tr>
											<tr class="pre_line">
												<th valign="top">値引き2:</th>
												<td>
													<input type="text" name="discount2" value="<?php echo $discount[1];?>" class="inp-form" onChange="reduce()">
												</td>
											</tr>
											<tr>
												<th valign="top">コース3:</th>
												<td><select name="course_id3" class="styledselect_form_3 guidance" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option>-</option><?php Reset_Select_Key_Group( $course_list , ($course_id[2] ? $course_id[2] : ($data['hp_flg'] ? 70 : 0)),$gCourseGroup);?></select></td>
											</tr>
											<tr><!-- 施術箇所選択 -->
												<th></th>
												<td class="single_inner <?php echo ($contract_part[2]) ? '' : 'parts_area'?>">
													<?php echo InputCheckboxTagKey("contract_parts3",$gContractParts,$contract_part[2],"")?>
												</td>
											</tr>
											<tr>
												<th valign="top">コース3金額（税込）:</th>
												<td><input type="text" name="fixed_price3" value="<?php echo TA_Cook($fixed_price[2] ? $fixed_price[2] : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" /></td>
											</tr>
											<tr class="pre_line">
												<th valign="top">値引き3:</th>
												<td>
													<input type="text" name="discount3" value="<?php echo $discount[2];?>" class="inp-form" onChange="reduce()">
												</td>
											</tr>
                                            <tr>
                                                <th valign="top">コース4:</th>
                                                <td><select name="course_id4" class="styledselect_form_3 guidance" onChange="keisan()" title="＊コースが未選択の場合は削除と見なす。"><option>-</option><?php Reset_Select_Key_Group( $course_list , ($course_id[3] ? $course_id[3] : ($data['hp_flg'] ? 70 : 0)),$gCourseGroup);?></select></td>
                                            </tr>
                                            <tr><!-- 施術箇所選択 -->
                                                <th></th>
                                                <td class="single_inner <?php echo ($contract_part[3]) ? '' : 'parts_area'?>">
                                                    <?php echo InputCheckboxTagKey("contract_parts4",$gContractParts,$contract_part[3],"")?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th valign="top">コース4金額（税込）:</th>
                                                <td><input type="text" name="fixed_price4" value="<?php echo TA_Cook($fixed_price[3] ? $fixed_price[3] : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" /></td>
                                            </tr>
                                            <tr class="pre_line">
                                                <th valign="top">値引き4:</th>
                                                <td>
                                                    <input type="text" name="discount4" value="<?php echo $discount[3];?>" class="inp-form" onChange="reduce()">
                                                </td>
                                            </tr>
											<tr class="menu_single">
												<th><label class="single_check" for="single_check">カスタマイズ:</label>
												<!-- チェックがあった場合のみ値を送信 -->
												<input type="hidden" name="single_course_id" value="">
												<input type="checkbox" id="single_check" class="guidance" name="single_course_id" value="49"; 
												<?php echo $_POST['single_course_id'] = ($single_course_id || $_POST['single_course_id']) ? "checked" : "";?>></th>
												<td class="single_inner  <?php echo ($single_part) ? '' : 'parts_area'?>">
													<?php echo InputCheckboxTagKey("contract_single",$gContractParts,$single_part,"")?>
												</td>
											</tr>
											<tr class="menu_single">
												<th valign="top">カスタマイズ金額（税込）:</th>
												<td><input type="text" name="single_fixed_price" value="<?php echo TA_Cook($single_fixed_price ? $single_fixed_price : ($data['hp_flg'] ? $hp_price : "")) ;?>" class="inp-form" /></td>
											</tr>
											<tr class="menu_single pre_line">
												<th valign="top">カスタマイズ値引き:</th>
												<td>
													<input type="text" name="single_discount" value="<?php echo $single_discount;?>" class="inp-form" onChange="reduce()">
												</td>
											</tr>
											<tr>
												<th valign="top">値引タイプ:</th>
												<td><select name="dis_type" class="styledselect_form_3" ><?php Reset_Select_Key( $gDisType , $sales['dis_type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">値引き(内)ポイント:</th>
												<td><input type="text" name="point" value="<?php echo TA_Cook($sales['point'] ? $sales['point'] : $data['point']) ;?>" class="inp-form" /></td>
											</tr>

											<tr>
												<th valign="top">ご請求金額（税込）:</th>
												<td><input id="price" type="text" name="price" value="<?php echo  TA_Cook($sales['price'] ? $sales['price'] : ($data['hp_flg'] ? ($hp_price-$data['point']- $hp_discount) : "")) ;?>" class="inp-form"/></td>
											</tr>
											<tr>
												<td colspan="2">
													<span id="reg_btn">入金入力へ</span>
												</td>
											</tr>
											<!--<tr>
												<th valign="top">支払方法:</th>
												<td><select name="pay_type" class="styledselect_form_1"><?php Reset_Select_Key( $gPayType , $sales['pay_type'] ? $sales['pay_type'] : 1);?></select></td>
											</tr>-->
											<tr>
												<th valign="top">入金(現金):</th>
												<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo TA_Cook($sales['payment_cash']) ;?>" class="inp-form" onChange="keisan()" readonly/></td>
											</tr>
											<tr>
												<th valign="top">入金(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo TA_Cook($sales['payment_card']) ;?>" class="inp-form" onChange="keisan()" readonly/></td>
											</tr>
											<tr>
												<th valign="top">入金(振込):</th>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form" onChange="keisan()" readonly/></td>
											</tr>
											<tr>
												<th valign="top">入金(ローン):</th>
												<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($sales['payment_loan']) ;?>" class="inp-form" onChange="keisan()" readonly/></td>
											</tr>

											<!--<tr>
												<th valign="top">入金(クーポン):</th>
												<td><input id="p_payment" type="tel" name="payment_coupon" value="<?php echo TA_Cook($sales['payment_coupon']) ;?>" class="inp-form" onChange="keisan()" /></td>
											</tr>
											<tr>
												<th valign="top">売掛金:</th>
												<td><input type="tel" name="balance" value="<?php echo TA_Cook($sales['balance']) ;?>" id="fm" class="inp-form" /></td>
											</tr> -->
											<tr>
												<th valign="top">オプション:</th>
												<td>
													<select name="option_name" class="styledselect_form_3" onChange="statement_option()"><?php Reset_Select_Key( $gOption1 , $sales['option_name']);?></select>
													<span id="shaving" <?php if(!$data['reg_flg'] || $sales['option_name'] ==0 || $sales['option_name'] ==1){ ?>class="parts_area"<?php } ?>><!-- 未契約、またはオプションが体験で無い場合 -->
														<span class="shaving button">＋シェービング代(5000円)</span>
													</span>
												</td>
											</tr>
											<tr>
												<th valign="top">オプション支払(現金):</th>
												<td><input type="text" name="option_price" value="<?php echo TA_Cook($sales['option_price']) ;?>" class="inp-form" onChange="statement()"/></td>
											</tr>
                      <tr>
                        <th valign="top">オプション支払(カード):</th>
                        <td><input type="text" name="option_card" value="<?php echo TA_Cook($sales['option_card']) ;?>" class="inp-form" onChange="statement()" /></td>
                      </tr>
											<!-- コース契約前 / コース契約後 -->
											<?php if((($data['type'] == 1 || $data['type'] == 32) && $data['pid']==0 && $data['reg_flg']==0) || (($data['type'] == 1 || $data['type'] == 32) && $data['pid']<>0 && $data['reg_flg']==1)){ ?>
											<tr>
												<th valign="top">カウンセリング担当:</th>
												<td><select name="cstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$contract['contract_date']) , ($contract['staff_id'] ? $contract['staff_id'] : $data['cstaff_id']) ,getDatalist5("shop",$data['shop_id']));?></select></td>
											</tr>
											<!-- キャンペーン体験のみ -->
											<?php } elseif(($data['type'] == 1 || $data['type'] == 32) && ($sales['option_name'] ==10 || $sales['option_name'] ==11 || $sales['option_name'] ==12 || $sales['option_name'] ==13)){ ?>
											<tr>
												<th valign="top">カウンセリング担当:</th>
												<td><select name="cstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id") , ($sales['staff_id'] ? $sales['staff_id'] : $data['cstaff_id']) ,getDatalist5("shop",$data['shop_id']));?></select></td>
											</tr>
											<?php }?>

											<?php if($data['type']==2){ ?>
											<tr>
												<th valign="top">施術主担当:</th>
												<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_id'],getDatalist5("shop",$data['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">施術サブ担当1:</th>
												<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub1_id'],getDatalist5("shop",$data['shop_id']));?></select></td>

											</tr>
											<tr>
												<th valign="top">施術サブ担当2:</th>
												<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub2_id'],getDatalist5("shop",$data['shop_id']));?></select></td>
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
										<td>
											<div class="ditail_middle">
												<select id="discount_rate" name="discount_rate" onChange="cut_rates()"><?php Reset_Select_Key( $gDiscountRate ,$_POST['discount_rate']);?></select>&nbsp;(割引率換算)
												<table id="expenditures">
													<tr><td colspan="2"><h1>ご購入明細</h1></td></tr></tr>
													<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
													<tr>
														<td id="course_name" class="reg"><?php echo $course_list[$course_id[0]];?></td>
														<td class="regPrice">￥<span id="course_price"><?php echo number_format($fixed_price[0]);?></span></td>
													</tr>
													<tr>
														<td class="reg">値引き1</td>
														<td class="regPrice">▲￥<span id="discount"><?php echo number_format($discount[0]);?></span></td>
													</tr>
													<tr>
														<td id="course_name2" class="reg"><?php echo $course_list[$course_id[1]];?></td>
														<td class="regPrice">￥<span id="course_price2"><?php echo number_format($fixed_price[1]);?></span></td>
													</tr>
													<tr>
														<td class="reg">値引き2</td>
														<td class="regPrice">▲￥<span id="discount2"><?php echo number_format($discount[1]);?></span></td>
													</tr>
													<tr>
														<td id="course_name3" class="reg"><?php echo $course_list[$course_id[2]];?></td>
														<td class="regPrice">￥<span id="course_price3"><?php echo number_format($fixed_price[2]);?></span></td>
													</tr>
													<tr>
														<td class="reg">値引き3</td>
														<td class="regPrice">▲￥<span id="discount3"><?php echo number_format($discount[2]);?>
														<!-- <?php echo number_format(TA_Cook($sales['discount'] ? $sales['discount'] : ($data['hp_flg'] ? ($data['point']+ $hp_discount) : ""))) ;?> --></span></td>
													</tr>
													<tr>
														<td id="course_name4" class="reg"><?php echo $course_list[$course_id[3]];?></td>
														<td class="regPrice">￥<span id="course_price4"><?php echo number_format($fixed_price[3]);?></span></td>
													</tr>
													<tr>
														<td class="reg">値引き4</td>
														<td class="regPrice">▲￥<span id="discount4"><?php echo number_format($discount[3]);?>
													</tr>
													<tr>
														<td id="single_course_name" class="reg"><?php echo $course_list[$single_course_id];?></td>
														<td class="regPrice">￥<span id="single_price"><?php echo number_format($single_fixed_price);?></span></td>
													</tr>
													<tr>
														<td class="reg">カスタマイズ値引き</td>
														<td class="regPrice">▲￥<span id="single_discount"><?php echo number_format($single_discount);?></span></td>
													</tr>
													<tr>
														<td id="option_name" class="reg"><?php echo $sales['option_name'] ? $gOption[$sales['option_name']] : "オプション";?></td>
														<td class="regPrice">￥<span id="option_price"><?php echo number_format($sales['option_price']+$sales['option_card']);?></span></td>
													</tr>
													<tr class="hr">
														<td class="regTotal">契約金合計(オプション代除く)</td>
														<td class="regTotalPrice">￥<span id="total"><?php echo number_format(TA_Cook($sales['price'] ? $sales['price'] : ($data['hp_flg'] ? ($hp_price-$data['point']- $hp_discount) : ""))) ;?></span></td>
													</tr>
													<tr>
														<td class="reg">内税（<?php echo $tax*100 ."%";?>）</td>
														<td class="regPrice">￥<span id="tax"><?php echo number_format($sales['price']-$sales['price']/$tax2);?></span></td>
													</tr>
													<tr class="hr">
														<td class="regTotal">本日支払合計</td>
														<td class="regTotalPrice">￥<span id="payment"><?php echo number_format($sales['payment']+ $sales['option_price']+ $sales['option_card']);?></span></td>
													</tr>
													<tr>
														<td class="reg">契約残金</td>
														<td class="regPrice">￥<span id="balance"><?php echo number_format($sales['balance']);?></span></td>
													</tr>
													<!-- <tr>
														<td class="reg">お預かり</td>
														<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan3()"/></td>
													</tr>
													<tr>
														<td class="reg">お釣り</td>
														<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
													</tr> -->
												</table>
												<?php if($complete_flg){?>
													<div align="right"><a href="./?pay_date=<?php echo $_POST['pay_date'] ;?>">レジ一覧へ<a></div>
												<?php } ?>
											</div>
										</td>
									<td>
										<!--  start related-activities -->
										<div class="related-activities">
											<!--  start related-act-top -->
											<div class="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title"><a href="../customer/edit.php?id=<?php echo $customer['id']?>" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->

											<!--  start related-act-bottom -->
											<div class="related-act-bottom">
												<!--  start related-act-inner -->
												<ul class="related-act-inner">
													<li class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></li>
													<li class="right"><h5>名前 : <?php echo $customer['name'] ? $customer['name'] : $customer['name_kana']?></h5></li>
													<!--
													<li class="right"><h5>カウンセリング担当 : <?php echo $staff_list[$customer['staff_id']]?></h5></li>
													-->
												</ul><!-- end related-act-inner -->
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
										<?php if($data['reg_flg']){?>

										<!--  start related-activities -->
										<div class="related-activities">
											<!--  start related-act-top -->
											<div class="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">出力</div>
											</div>
											<!-- end related-act-top -->

											<!--  start related-act-bottom -->
											<div class="related-act-bottom">
												<!--  start related-act-inner -->
												<div class="related-act-inner">

													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right">
														<h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_reg_detail.php<?php echo $mpdf_reg_detail;?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" title="領収書発行へ">領収書</a></h5>

													</div>
												</div><!-- end related-act-inner -->
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
<script src="../js/jquery.guidance.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/jquery.guidance.css">
<script type="text/javascript" charset="utf-8">
/*  add201603 説明文の表示 */
	$(".guidance").guidance({
		Itargetval : "49",
		Stargetval : "1,5,8,12,16,20",
		guidanceAll : "1回コースの有効期限は1ヶ月です！<br>同じ部位を複数購入しないでください。"
	});
/*  add201601  */
	// カスタマイズ金額の計算
	$(".single_inner input[type=checkbox]").on("change",function(){
		one_detail(this);
	});
		// 金額を固定するボタン
		$("#reg_btn").on("click",function(){
			if(this.classList == "disabled"){
				release(this);
			}else{
				reg_btn(this);
			}
		});
		//コース金額
  var course_prices_str = '<?php echo $course_prices;?>';
  var course_prices = course_prices_str.split(',');        //文字列をカンマで分解し、配列化
  var part_course_prices_str = JSON.parse('<?php echo $part_course_prices; ?>'); //カスタマイズの金額
  var course_single = JSON.parse('<?php echo $part_course_names; ?>'); //明細に表示するカスタマイズ部位名
  var option = JSON.parse('<?php echo json_encode($gOption1); ?>');
  var tax2 = '<?php echo $tax2;?>';
</script>
<script src="../js/detail.js" type="text/javascript" charset="utf-8"></script>
<?php include_once("../include/footer.html");?>

