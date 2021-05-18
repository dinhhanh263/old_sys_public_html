<?php include_once("../library/muryou/edit.php");?>
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
		<div id="page-heading"><h1>無料会員詳細</h1></div>
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
												<th valign="top">お名前:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">オナマエ(カナ):</th>
												<td><input type="text" name="name_kana" value="<?php echo TA_Cook($data['name_kana']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">生年月日</th>
												<td><input type="text" name="birthday" value="<?php echo TA_Cook($data['birthday']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">年齢:</th>
												<td><input type="text" name="age" value="<?php echo TA_Cook($data['age']) ;?>" class="inp-form" /></td>
											</tr>

<!--
											<tr>
												<th valign="top">性別:</th>
												<th valign="bottom">
													<input type="radio" name="sex" value="2" <?php echo $data['sex']=="2" ? "checked" : "";?> class="form-checkbox" />女性
													<input type="radio" name="sex" value="1" <?php echo $data['sex']=="1" ? "checked" : "";?> class="form-checkbox" />男性
												</th>
											</tr>
-->

											<tr>
												<th valign="top">郵便番号</th>
												<td><input type="text" name="zip" value="<?php echo TA_Cook($data['zip']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">都道府県:<?php echo $data['pref']; ?></th>
												<td><select name="pref" class="styledselect_form_1" ><?php Reset_Select_Key($gPref2 ,$data['pref']); ?></select></td>
											</tr>
											<tr>
												<th valign="top">住所</th>
												<td><input type="text" name="address" value="<?php echo TA_Cook($data['address']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">メールアドレス</th>
												<td><input type="text" name="mail" value="<?php echo TA_Cook($data['mail']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">電話番号</th>
												<td><input type="text" name="tel" value="<?php echo TA_Cook($data['tel']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">facebook</th>
												<td><input type="text" name="facebook" value="<?php echo TA_Cook($data['facebook']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">プレゼント</th>
												<td>
													<select name="pref" class="styledselect_form_1" >
														<?php Reset_Select_Key( $gPresent , $data['present']);?>
													</select>
												</td>

											</tr>
											<tr>
												<th valign="top">広告コード</th>
												<td><input type="text" name="adcode" value="<?php echo TA_Cook($data['adcode']) ;?>" class="inp-form" /></td>
											</tr>

											<tr>
												<th valign="top">登録状況:</th>
												<th valign="bottom">
													<input type="radio" name="reg_flg" value="0" <?php echo $data['reg_flg']=="0" ? "checked" : "";?> class="form-checkbox" />仮登録
													<input type="radio" name="reg_flg" value="1" <?php echo $data['reg_flg']=="1" ? "checked" : "";?> class="form-checkbox" />本登録
												</th>
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