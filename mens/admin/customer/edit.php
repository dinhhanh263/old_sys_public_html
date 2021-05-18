<?php include_once("../library/customer/edit.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>顧客詳細</h1></div>
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
								<td>
									<!-- start id-form -->
									<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<?php echo $gMsg;?>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">会員番号:</th>
												<td><?php echo $data['no'];?></td>
											</tr>
											<tr>
												<th valign="top">会員タイプ:</th>
												<td><select name="ctype" class="styledselect_form_1"><?php Reset_Select_Key( $gCustomerType , $data['ctype']);?></td>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td>
													<input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" id="Name"  class="inp-form" />
												</td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">姓と名の間にスペースを入れてください。</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr>
												<th valign="top">名前(カナ):</th>
												<td>
													<input type="text" name="name_kana" value="<?php echo TA_Cook($data['name_kana']) ;?>" id="NameKana" class="inp-form" />
												</td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">姓と名の間にスペースを入れてください。</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
										<?php if($authority_level<=6 || $authority['id']==106 && $data['ctype']<2){?>
											<tr>
												<th valign="top">郵便番号:</th>
												<td><input type="tel" name="zip" value="<?php echo TA_Cook($data['zip']) ;?>" class="inp-form" onKeyUp="AjaxZip2.zip2addr(this,'pref','address');" /></td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner"><a style="color:#2e74b2;" href="javascript:window.open('http://www.post.japanpost.jp/zipcode/', '_blank', 'width=700, height=950, menubar=no, toolbar=no, scrollbars=yes');">郵便番号を検索</a></div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr>
												<th valign="top">都道府県:</th>
												<td><select name="pref" class="styledselect_form_1"><?php Reset_Select_Key( $gPref , $data['pref']);?></select></td>
											</tr>
											<tr>
												<th valign="top">住所:</th>
												<td><input type="text" name="address" value="<?php echo TA_Cook($data['address']) ;?>" class="inp-form" /></td>
											</tr>
										<?php }?>
											<tr>
												<th valign="top">生年月日:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr valign="top">
															<td><input class="inp-form"  name="birthday" type="text" id="day2" value="<?php echo $data['birthday']=="0000-00-00" ? "" : $data['birthday'] ;?>" /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">年齢:</th>
												<td>
													<input type="tel" name="age" value="<?php echo TA_Cook($data['age']) ;?>" id="fm" class="inp-form" />
												</td>
											</tr>
											<tr>
												<th valign="top">BIG:</th>
												<td><select name="big_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gBig , $data['big_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">親権者同意書:</th>
												<td><select name="agree_status" class="styledselect_form_1"><?php Reset_Select_Key( $gAgreeStatus , $data['agree_status']);?></select></td>
											</tr>
											<tr>
												<th valign="top">委任状:</th>
												<td><select name="attorney_status" class="styledselect_form_1"><?php Reset_Select_Key( $gAttorneyStatus , $data['attorney_status']);?></select></td>
											</tr>
											<tr>
												<th valign="top">原本郵送:</th>
												<td><select name="contract_send" class="styledselect_form_1"><?php Reset_Select_Key( $gContractSend , $data['contract_send']);?></select></td>
											</tr>
											<tr>
												<th valign="top">学生証明:</th>
												<td><select name="student_id" class="styledselect_form_1"><?php Reset_Select_Key( $gStudentID , $data['student_id']);?></select></td>
											</tr>
											<tr class="vip-treatment">
												<th valign="top">役職者対応:</th>
												<td><select name="sv_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gSVNeed , $data['sv_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">CBS会員番号:</th>
												<td><input  class="inp-form" name="cbs_no" type="text" value="<?php echo $data['cbs_no'];?>" /></td>
											</tr>
											<tr class="loan-rate">
												<th valign="top">ローン延滞者:</th>
												<td><select name="loan_delay_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gLoanNG , $data['loan_delay_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">デジキャット引落NG:</th>
												<td><select name="digicat_ng_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gCardNG , $data['digicat_ng_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">ネクストペイメント端末決済NG:</th>
												<td><select name="nextpay_end_ng_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gCardNG , $data['nextpay_end_ng_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">ネクストペイメントオペレーター決済NG:</th>
												<td><select name="nextpay_op_ng_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gCardNG , $data['nextpay_op_ng_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">銀行引落NG:</th>
												<td><select name="bank_ng_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gBankNG , $data['bank_ng_flg']);?></select></td>
											</tr>
											<!--<tr>
												<th valign="top">性別:</th>
												<td><select name="sex"  class="styledselect_form_1"><?php Reset_Select_Key( $gSex2 , $data['sex']);?></select></td>
											</tr> -->
											<!--<tr>
												<th valign="top">携帯電話:</th>
												<td>
													<input type="text" name="mobile" value="<?php echo TA_Cook($data['mobile']) ;?>" id="fm" class="inp-form" />
												</td>
											</tr>-->
										<?php if($authority_level<=6 || $authority['id']==106 && $data['ctype']<2){?>
											<tr>
												<th valign="top">電話番号:</th>
												<td>
													<input type="text" name="tel" value="<?php echo TA_Cook($data['tel']) ;?>" id="fm2" class="inp-form" />
												</td>
											</tr>
											<tr>
												<th valign="top">メールアドレス:</th>
												<td>
													<input type="text" name="mail" value="<?php echo TA_Cook($data['mail']) ;?>" id="fm3" class="inp-form" />
												</td>
											</tr>
										<?php }?>
											<tr>
												<th valign="top">登録店舗:</th>
												<td><select name="shop_id" class="styledselect_form_1  <?php echo $_POST['id'] ? "disabled" : "" ?>" ><?php Reset_Select_Key( $shop_list , $data['shop_id'] ? $data['shop_id'] : $authority_shop['id'] );?></select></td>
												<!--<td><select name="shop_id" class="styledselect_form_1"><?php Reset_Select_Key( $shop_list , $data['shop_id'] ? $data['shop_id'] : $authority_shop['id'] );?></select></td>-->
											</tr>

											<tr>
												<th valign="top">登録日時:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">

															<td><input  class="inp-form" name="" type="text" value="<?php echo $data['reg_date'];?>" disabled/></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">カウンセリング日時:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input  class="inp-form" name="" type="text" value="<?php echo $counseling['hope_date']." ".$gTime2[$counseling['hope_time']];?>" disabled/></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">カウンセリング担当:</th>
												<td><input  class="inp-form" name="" type="text" value="<?php echo $staff_list[$counseling['cstaff_id']];?>" disabled /></td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">紹介者:</th>
												<td><input  class="inp-form" name="introducer" type="text" value="<?php echo $data['introducer'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">紹介者タイプ:</th>
												<td>
													<select name="introducer_type" class="styledselect_form_1"><?php Reset_Select_Key( $gIntroducerType , $data['introducer_type']);?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">紹介企業:</th>
												<td>
													<select name="partner" class="styledselect_form_1"><?php Reset_Select_Key( $partner_list , $data['partner']);?></select>
												</td>
											</tr>
										<?php if($authority_level<=1){?>
											<tr>
												<th valign="top">特別紹介者:</th>
												<td>
													<select name="special" class="styledselect_form_1"><?php Reset_Select_Key( $special_list , $data['special']);?></select>
												</td>
											</tr>
										<?php  } ?>
											<tr>
												<th valign="top">カード名義（カナ）:</th>
												<td><input  class="inp-form" name="card_name_kana" type="text" value="<?php echo $data['card_name_kana'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">カード名義(ローマ字):</th>
												<td><input  class="inp-form" name="card_name" type="text" value="<?php echo $data['card_name'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">カード下4桁:</th>
												<td><input  class="inp-form" name="card_no" type="text" value="<?php echo $data['card_no'];?>" /></td>
											</tr>

											<!--<tr>
												<th valign="top">紹介数:</th>
												<td class="noheight"></td>
												<td></td>
											</tr>

											<tr>
												<th valign="top">来店回数:</th>
												<td class="noheight"></td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">消化回数:</th>
												<td class="noheight">5(<a href="">カルテ</a>)</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">契約書:</th>
												<td class="noheight"></td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">同意書:</th>
												<td class="noheight"></td>
												<td></td>
											</tr>-->

											<?php if($data['pair_name_kana']){?>
											<tr>
												<th valign="top">同伴者名前:</th>
												<td><input  class="inp-form" name="pair_name_kana" type="text" value="<?php echo $data['pair_name_kana'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">同伴者TEL:</th>
												<td><input type="text" name="pair_tel" value="<?php echo TA_Cook($data['pair_tel']) ;?>" id="fm3" class="inp-form" /></td>
											</tr>

											<?php  } ?>
											<tr>
												<th valign="top">参照元:</th>

												<td><textarea name="" class="form-textarea2" disabled><?php echo substr($data['referer_url'], 0,100);?></textarea></td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">閲覧プラウザ:</th>

												<td><textarea name="" class="form-textarea2" disabled><?php echo TA_Cook($data['user_agent']) ;?></textarea></td>
												<td></td>
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
									</form>
									<!-- end id-form  -->
								</td>
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div>
					<!--  end content-table-inner  -->
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
	new AutoKana('FirstName', 'FirstNameKana', {katakana: true, toggle: false});
	new AutoKana('LastName', 'LastNameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>