<?php include_once("../library/job/offer_media_edit.php");?>
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
		<div id="page-heading"><h1>求人媒体詳細</h1></div>
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
										<div id="inp-form">
											<ul class="registration-cnt">
												<li><!-- 求人媒体ID -->
													<span class="registration-ttl">求人媒体ID：</span>
													<?php echo $data['id'] ?>
												</li>
												<li><!-- 媒体名 -->
													<span class="registration-ttl">媒体名：</span>
													<div class="registration-box">
														<input type="text" name="name" value="<?php echo TA_Cook($data['name']) ;?>" class="registration-form" />
													</div>
												</li>
												<li><!-- 公開/非公開 -->
													<span class="registration-ttl">公開/非公開：</span>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="status" value="0" <?php echo $data['status']=="0" ? "checked" : "";?> />公開
													</label>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="status" value="1" <?php echo $data['status']=="1" ? "checked" : "";?> />非公開
													</label>
												</li>
												<li><!-- 主な求人種類 -->
													<span class="registration-ttl">主な求人種類：</span>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="type" value="1" <?php echo $data['type']=="1" ? "checked" : "";?> />正社員
													</label>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="type" value="1" <?php echo $data['type']=="2" ? "checked" : "";?> />契約社員
													</label>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="type" value="1" <?php echo $data['type']=="3" ? "checked" : "";?> />アルバイト・パート
													</label>
												</li>
												<li><!-- 求人媒体利用開始日 -->
														<span class="registration-ttl">公開開始日：</span>
														<input type="text" name="start_date" value="<?php echo TA_Cook($data['start_date']) ;?>" class="registration-form w7" placeholder="2020-01-01" />
												</li>
												<li><!-- 求人媒体利用終了日 -->
														<span class="registration-ttl">公開終了日：</span>
														<input type="text" name="end_date" value="<?php echo $data['end_date'];?>" class="registration-form w7" placeholder="2020-01-01" /><span class="previous-item">※指定無い場合は未記入</span>
												</li>
											</ul>
											<span class="btn-area">
												<input type="reset" value="reset" class="reset" />
												<input type="submit" value="登録する" class="submit" />
											</span>
										</div>
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