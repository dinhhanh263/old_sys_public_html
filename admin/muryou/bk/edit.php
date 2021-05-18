<?php include_once("../library/job/edit.php");?>
<?php include_once("../include/header_menu.html");?>
<script src="../js/ajaxzip2/ajaxzip2.js" charset="UTF-8"></script>
<script type="text/javascript">
    AjaxZip2.JSONDATA = '../js/ajaxzip2/data';
</script>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>求人詳細</h1></div>
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
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">お名前(姓):</th>
												<td><input type="text" name="entry_name" value="<?php echo TA_Cook($data['entry_name']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">お名前(名):</th>
												<td><input type="text" name="entry_name_2" value="<?php echo TA_Cook($data['entry_name_2']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">オナマエ(セイ)[カナ]:</th>
												<td><input type="text" name="entry_name_kana" value="<?php echo TA_Cook($data['entry_name_kana']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">オナマエ(メイ)[カナ]:</th>
												<td><input type="text" name="entry_name_kana_2" value="<?php echo TA_Cook($data['entry_name_kana_2']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">性別:</th>
												<th valign="bottom">
													<input type="radio" name="sex" value="sex_s01" <?php echo $data['sex']=="sex_s01" ? "checked" : "";?> class="form-checkbox" />女性
													<input type="radio" name="sex" value="sex_s00" <?php echo $data['sex']=="sex_s00" ? "checked" : "";?> class="form-checkbox" />男性
												</th>
											</tr>
											<tr>
												<th valign="top">年齢:</th>
												<td><input type="text" name="age" value="<?php echo TA_Cook($data['age']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">生年月日</th>
												<td><input type="text" name="birthday" value="<?php echo TA_Cook($data['birthday']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="zip">郵便番号</th>
												<td><input type="text" name="zip" value="<?php echo TA_Cook($data['zip']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">都道府県:<?php echo $data['pref']; ?></th>
												<td><select name="pref" class="styledselect_form_1" ><?php Reset_Select_Key($gPref2 ,$data['pref']); ?></select></td>
											</tr>
											<tr>
												<th valign="top">市区町村</th>
												<td><input type="text" name="now_address_1" value="<?php echo TA_Cook($data['now_address_1']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">番地、ビル、マンション</th>
												<td><input type="text" name="now_address_2" value="<?php echo TA_Cook($data['now_address_2']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">電話番号</th>
												<td><input type="text" name="now_tel_1" value="<?php echo TA_Cook($data['now_tel_1']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">携帯番号</th>
												<td><input type="text" name="now_tel_2" value="<?php echo TA_Cook($data['now_tel_2']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">メールアドレス</th>
												<td><input type="text" name="now_email" value="<?php echo TA_Cook($data['now_email']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">最寄り線</th>
												<td><input type="text" name="line" value="<?php echo TA_Cook($data['line']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">最寄り駅</th>
												<td><input type="text" name="station" value="<?php echo TA_Cook($data['station']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">ご希望店舗</th>
												<td><input type="text" name="shop" value="<?php echo TA_Cook($data['shop']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">採用対象</th>
												<td><input type="text" name="type" value="<?php echo TA_Cook($data['type']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">脱毛サロン勤務経験</th>
												<td><input type="text" name="exeperience_c" value="<?php echo TA_Cook($data['exeperience_c']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">ご応募のきっかけ</th>
												<td><input type="text" name="opportunity" value="<?php echo TA_Cook($data['opportunity']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">自己PR</th>
												<td><input type="text" name="input_form_title_tab_self_pr" value="<?php echo TA_Cook($data['input_form_title_tab_self_pr']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">ご質問等</th>
												<td><input type="text" name="comment" value="<?php echo TA_Cook($data['comment']) ;?>" class="inp-form" /></td>
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
</script>
<?php include_once("../include/footer.html");?>