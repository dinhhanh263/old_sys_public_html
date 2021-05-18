<?php include_once("../library/staff/edit.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!--全角英数字、ハイフン->半角--> 
<script type="text/javascript"> 
$(function() {
  $('#fm').change(function(){
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
		<div id="page-heading"><h1>従業員詳細</h1></div>
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
										<input type="hidden" name="select_shop_id" value="<?php echo $_GET['shop_id'];?>" />
										<input type="hidden" name="select_type" value="<?php echo $_GET['type'];?>" />
										<input type="hidden" name="select_class" value="<?php echo $_GET['class'];?>" />
										<input type="hidden" name="select_status" value="<?php echo $_GET['status'];?>" />
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">従業員No:</th>
												<td><input type="text" name="code" value="<?php echo TA_Cook($data['code']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">公開・非公開:</th>
												<th valign="bottom">
													<input type="radio" name="status" value="1" <?php echo $data['status']==1 ? "checked" : "";?> class="form-checkbox" />非公開
													<input type="radio" name="status" value="2" <?php echo $data['status']==2 ? "checked" : "";?> class="form-checkbox" />公開
												</th>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" id="Name"  class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">名前(カナ):</th>
												<td><input type="text" name="name_kana" value="<?php echo TA_Cook($data['name_kana']) ;?>" id="NameKana" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">所属:</th>
												<td><select name="shop_id" class="styledselect_form_1"><?php Reset_Select_Key( $shop_list , $data['shop_id']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">役職:</th>
												<td><select name="type" class="styledselect_form_1"><?php Reset_Select_Key( $gStaffType , $data['type']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">期別:</th>
												<td><select name="class" class="styledselect_form_1"><?php Reset_Select_Key( $gClass , $data['class']);?></select></td>
											</tr>
											<tr>
												<th valign="top">新人:</th>
												<td><input type="checkbox" name="new_face" value="1" <?php if($data['new_face']) echo "checked";?> class="form-checkbox" /></td>
											</tr> 
											<tr>
												<th valign="top">施術のみ:</th>
												<td><input type="checkbox" name="treat_only" value="1" <?php if($data['treat_only']) echo "checked";?> class="form-checkbox" /></td>
											</tr> 
											<tr>
												<th valign="top">性別:</th>
												<?php $sex = $data['sex'] ? $data['sex'] : 2 ;?>
												<td><select name="sex" class="styledselect_form_1"><?php Reset_Select_Key( $gSex2 , $sex);?></select></td>
											</tr> 
											<tr>
												<th valign="top">メールアドレス:</th>
												<td><input type="text" name="email" value="<?php echo TA_Cook($data['email']) ;?>" id="fm" class="inp-form" /></td>
											</tr> 
											<tr>
												<th valign="top">電話番号:</th>
												<td><input type="text" name="tel" value="<?php echo TA_Cook($data['tel']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">住所:</th>
												<td><input type="text" name="address" value="<?php echo TA_Cook($data['address']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">生年月日:</th>
												<td><input type="text" name="birthday" value="<?php echo TA_Cook($data['birthday']<>'0000-00-00' ? $data['birthday'] : "") ;?>" id="fm" placeholder="0000-00-00" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">入社年月日:</th>
												<td><input type="text" name="begin_day" value="<?php echo TA_Cook($data['begin_day']<>'0000-00-00' ? $data['begin_day'] : "") ;?>" id="fm" placeholder="0000-00-00" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">退職年月日:</th>
												<td><input type="text" name="end_day" value="<?php echo TA_Cook($data['end_day']<>'0000-00-00' ? $data['end_day'] : "") ;?>" id="fm" placeholder="0000-00-00" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">ログインID:</th>
												<td><input type="text" name="login_id" value="<?php echo TA_Cook($data['login_id']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">パスワード:</th>
												<td><input type="text" name="password" value="<?php echo TA_Cook($data['password']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th>写真 :</th>
												<td><input type="file" name="photo" class="file_1" /></td>
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
								<?php if($data['photo']) {?>
								<td>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<div class="title">写真</div>
											</div>
											<!-- end related-act-top -->
		
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<img src="<?php  echo IMG_STAFF_DIR.$data['photo'];?>" width="230" />
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
								</td>
								<?php }?>
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