<?php include_once("../library/course/edit.php");?>
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
		<div id="page-heading"><h1>パーツ詳細</h1></div>
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
												<th valign="top">No:</th>
												<td><?php echo sprintf("%06s",$data["id"]) ;?></td>
											</tr>
											<tr>
												<th valign="top">公開・非公開:</th>
												<th valign="bottom">
													<input type="radio" name="status" value="1" <?php echo $data['status']==1 ? "checked" : "";?> class="form-checkbox" />非公開
													<input type="radio" name="status" value="2" <?php echo $data['status']==2 ? "checked" : "";?> class="form-checkbox" />公開
												</th>
											</tr>
											<tr>
												<th valign="top">タイプ:</th>
												<td><select name="type" class="styledselect_form_1"><?php Reset_Select_Key( $gCourseType , $data['type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">パーツ名:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">回数:</th>
												<td><input type="text" name="times" value="<?php echo $data['times'] ? TA_Cook($data['times']) : "" ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">保証期間(日):</th>
												<td><input type="text" name="period" value="<?php echo $data['period'] ? TA_Cook($data['period']) : "" ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" class="styledselect_form_1"><?php Reset_Select_Key( $gLength , $data['length']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">金額:</th>
												<td><input type="text" name="price" value="<?php echo TA_Cook($data['price']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">グループ:</th>
												<td><select name="group_id" class="styledselect_form_1"><?php Reset_Select_Key( $gCourseGroup2 , $data['group_id']);?></select></td>
											</tr>
											<tr>
												<th valign="top">旧パーツ:</th>
												<td>
													<input type="checkbox" name="old_flg" value="1" <?php if($data['old_flg']) echo "checked";?> class="form-checkbox" />
												</td>
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

<?php include_once("../include/footer.html");?>