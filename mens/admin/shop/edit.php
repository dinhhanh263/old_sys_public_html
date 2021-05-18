<?php include_once("../library/shop/edit.php");?>
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
		<div id="page-heading"><h1>店舗(部門)詳細</h1></div>
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
												<th valign="top">店舗コード:</th>
												<td><input type="text" name="code" value="<?php echo TA_Cook($data['code']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">公開・非公開:</th>
												<th valign="bottom">
													<input type="radio" name="status" value="1" <?php echo $data['status']==1 ? "checked" : "";?> class="form-checkbox" />非公開
													<input type="radio" name="status" value="2" <?php echo $data['status']==2 ? "checked" : "";?> class="form-checkbox" />公開
												</th>
											</tr>
											<tr>
												<th valign="top">店(部門)名:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" id="Name" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">店(部門)名(カナ):</th>
												<td><input type="text" name="name_kana" value="<?php echo TA_Cook($data['name_kana']) ;?>" id="NameKana" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">略名:</th>
												<td><input type="text" name="name_alias" value="<?php echo TA_Cook($data['name_alias']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">先行予約日:</th>
												<td><input type="text" name="rsv_date" value="<?php echo TA_Cook($data['rsv_date']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">オープン日:</th>
												<td><input type="text" name="open_date" value="<?php echo TA_Cook($data['open_date']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">エリア:</th>
												<td><select name="area" class="styledselect_form_1"><?php Reset_Select_Key( $gArea , $data['area']);?></select></td>
											</tr>
											<tr>
												<th valign="top">郵便番号:</th>
												<td><input type="text" name="zip" value="<?php echo TA_Cook($data['zip']) ;?>" class="inp-form" onKeyUp="AjaxZip2.zip2addr(this,'pref','address');" /></td>
											</tr>
											<tr>
												<th valign="top">都道府県:</th>
												<td><select name="pref" class="styledselect_form_1" ><?php Reset_Select_Key( $gPref , $data['pref']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">住所:</th>
												<td><input type="text" name="address" value="<?php echo TA_Cook($data['address']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">メールアドレス:</th>
												<td><input type="text" name="mail" value="<?php echo TA_Cook($data['mail']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">電話番号:</th>
												<td><input type="text" name="tel" value="<?php echo TA_Cook($data['tel']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">営業時間:</th>
												<td><input type="text" name="open_time" value="<?php echo TA_Cook($data['open_time']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">定休日:</th>
												<td><input type="text" name="day_off" value="<?php echo TA_Cook($data['day_off']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">ルーム数:</th>
												<td><input type="text" name="counseling_rooms" value="<?php echo TA_Cook($data['counseling_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<!--<tr>
												<th valign="top">VIPルーム数:</th>
												<td><input type="text" name="vip_rooms" value="<?php echo TA_Cook($data['vip_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">施術ルーム数:</th>
												<td><input type="text" name="medical_rooms" value="<?php echo TA_Cook($data['medical_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">パック専用ルーム数:</th>
												<td><input type="text" name="pack_rooms" value="<?php echo TA_Cook($data['pack_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>-->
											<tr>
												<td>========================</td>
												<td>============================</td>
											</tr>
											<tr>
												<th valign="top">実ルーム数:</th>
												<td><input type="text" name="current_c_rooms" value="<?php echo TA_Cook($data['current_c_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<!--<tr>
												<th valign="top">実際施術ルーム数:</th>
												<td><input type="text" name="current_m_rooms" value="<?php echo TA_Cook($data['current_m_rooms']) ;?>" id="fm" class="inp-form" /></td>
											</tr>-->
											<tr>
												<th valign="top">アクセス:</th>
												<td><textarea name="access" class="form-textarea"><?php echo TA_Cook($data['access']) ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">カード使用:</th>
												<td>
													<input type="checkbox" name="card" value="1" <?php if($data['card']) echo "checked";?> class="form-checkbox" />
												</td>
											</tr>
											<tr>
												<th valign="top">駐車場:</th>
												<td>
													<input type="checkbox" name="park" value="1" <?php if($data['park']) echo "checked";?> class="form-checkbox" />
												</td>												</td>
											</tr>
											<tr>
												<th>写真1 :</th>
												<td><input type="file" name="shop_img" class="file_1" /></td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">JPEG, GIF 5MB max per image</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr><th></th><td>
												<?php if($data['shop_img']){?>
												<img src="<?php echo IMG_SHOP_DIR.$data['shop_img'];?>" />&nbsp;[<a href="edit.php?id=<?php echo $data['id']?>&mode=delete_image&image_type=shop_img">写真を削除</a>]
												<?php }?>
											</td></tr>
											<tr>
												<th>写真2 :</th>
												<td><input type="file" name="shop_img2" class="file_1" /></td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">JPEG, GIF 5MB max per image</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr><th></th><td>
												<?php if($data['shop_img2']){?>
												<img src="<?php echo IMG_SHOP_DIR.$data['shop_img2'];?>" />&nbsp;[<a href="edit.php?id=<?php echo $data['id']?>&mode=delete_image&image_type=shop_img2">写真を削除</a>]
												<?php }?>
											</td></tr>
											<tr>
												<th valign="top">お知らせ:</th>
												<td><textarea name="notice" class="form-textarea"><?php echo TA_Cook($data['notice']) ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">備考:</th>
												<td><textarea name="memo" class="form-textarea"><?php echo TA_Cook($data['memo']) ;?></textarea></td>
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