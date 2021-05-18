<?php include_once("../library/adcode/blog_edit.php");?>
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
		<div id="page-heading"><h1>ブログ詳細</h1></div>
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
												<th valign="top">公開日付:</th>
												<td><input type="text" name="blog_date" value="<?php echo TA_Cook($data['blog_date']) ;?>" class="inp-form" placeholder="<?php echo date("Y-m-d")?>" /></td>
											</tr>
											<tr>
												<th valign="top">公開・非公開:</th>
												<th valign="bottom">
													<input type="radio" name="status" value="1" <?php echo $data['status']<>2 ? "checked" : "";?> class="form-checkbox" />非公開
													<input type="radio" name="status" value="2" <?php echo $data['status']==2 ? "checked" : "";?> class="form-checkbox" />公開
												</th>
											</tr>
											<tr>
												<th valign="top">タイトル:</th>
												<td><input type="text" name="title" value="<?php echo TA_Cook($data['title']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">URL:</th>
												<td><input type="text" name="url" value="<?php echo TA_Cook($data['url']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">スパム抑制（URL）:</th>
												<td><input type="checkbox" name="url_nofollow" value="1" class="form-checkbox" <?php echo $data['url_nofollow'] ? "checked" : "" ;?> /></td>
											</tr>
											<tr>
												<th valign="top">新ウィンドウで表示(URL):</th>
												<td><input type="checkbox" name="url_blank" value="1" class="form-checkbox" <?php echo $data['url_blank'] ? "checked" : "" ;?> /></td>
											</tr>
											<tr>
												<th valign="top">画像:</th>
												<td><input type="file" name="img_file" style="width:200px;" class="file_1" /></td>
											</tr>
											<tr>
												<th></th>
												<?php if($data['img_name']){?>
												<td>
												<img src="<?php echo IMG_DIR.$data['img_name'];?>" width="100" height="100"/>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner"><a style="color:#2e74b2;" href="blog_edit.php?id=<?php echo $data['id']?>&mode=delete_image&image_type=img_file" onclick="return confirm(\'写真を削除しますか？\')">写真を削除</a></div>
													<div class="bubble-right"></div>
												</td>
												<?php }?>
											</tr>
											<tr>
												<th valign="top">コメント:</th>
												<td><textarea name="comment" class="form-textarea2"><?php echo TA_Cook($data['comment']) ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">表示順位:</th>
												<td><input type="text" name="rank" value="<?php echo TA_Cook($data['rank']) ;?>" class="inp-form" /></td>
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