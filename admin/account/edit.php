<?php include_once("../library/reservation/edit.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>予約詳細</h1></div>
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
										<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />

										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" style="height:33px;"><?php Reset_Select_Key( $gResType , $data['type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">会員番号:</th>
												<td>
													<?php if(isset($data)) echo TA_Cook($customer['no']);else{?>
													<input type="text" name="no" value="<?php echo TA_Cook($customer['no']) ;?>" id="fm" class="inp-form-error" />
													<?php } ?>
												</td>
											</tr>
											<!--<tr>
												<th valign="top">公開・非公開:</th>
												<th valign="bottom">
													<input type="radio" name="status" value="1" <?php echo $data['status']==1 ? "checked" : "";?> class="form-checkbox" />非公開
													<input type="radio" name="status" value="2" <?php echo $data['status']==2 ? "checked" : "";?> class="form-checkbox" />公開
												</th>
											</tr>-->
											<tr>
												<th valign="top">名前:</th>
												<td>
													<?php if(isset($data)) echo TA_Cook($customer['name']);else{?>
													<input type="text" name="name" value="<?php echo TA_Cook($customer['name']) ;?>" id="Name"  class="inp-form-error" />
													<?php } ?>
												</td>
											</tr>
											<tr>
												<th valign="top">名前(カナ):</th>
												<td>
													<?php if(isset($data)) echo TA_Cook($customer['name_kana']);else{?>
													<input type="text" name="name_kana" value="<?php echo TA_Cook($customer['name_kana']) ;?>" id="NameKana" class="inp-form-error" />
													<?php } ?>
												</td>
											</tr>
											<tr>
												<th valign="top">電話番号:</th>
												<td>
													<?php if(isset($data)) echo TA_Cook($customer['tel']);else{?>
													<input type="text" name="tel" value="<?php echo TA_Cook($customer['tel']) ;?>" id="fm" class="inp-form-error" />
													<?php } ?>
												</td>
											</tr>
											<tr>
												<th valign="top">メールアドレス:</th>
												<td>
													<?php if(isset($data)) echo TA_Cook($customer['mail']);else{?>
													<input type="text" name="mail" value="<?php echo TA_Cook($customer['mail']) ;?>" id="fm" class="inp-form-error" />
													<?php } ?>
												</td>
											</tr>
											<tr>
												<th valign="top">店舗:</th>
												<td>
													<!-- <select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $data['shop_id'] );?></select> -->
													<select id="shop_id" name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">ルーム:</th>
												<td><select name="room_id" style="height:33px;"><?php Reset_Select_Key( $room_list , $data['room_id']);?></select></td>
											</tr>
											<tr>
												<th valign="top">（契約）コース:</th>
												<td><select name="course_id" style="height:33px;"><?php Reset_Select_Key( $course_list , $data['course_id']);?></select></td>
											</tr>
											<tr>
												<th valign="top">来店日時:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input style="width:70px;height:23px;" name="hope_date" type="text" id="day" value="<?php echo $data['hope_date'];?>" /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">開始時間:</th>
												<td>
													<select size="1" name="hope_time" style="height:33px;"><?php Reset_Select_Key( $gTime  , $data['hope_time']);?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" style="height:33px;"><?php Reset_Select_Key( $gLength , $data['length']);?></select></td>
											</tr>
											<tr>
												<th valign="top">経由:</th>
												<td><select name="root" style="height:33px;"><?php Reset_Select_Key( $gRoot , $data['root']);?></select></td>
											</tr>
											<tr>
												<th valign="top">ご希望・ご質問:</th>
												<td><textarea name="comment" class="form-textarea"><?php echo TA_Cook($data['comment']) ;?></textarea></td>
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