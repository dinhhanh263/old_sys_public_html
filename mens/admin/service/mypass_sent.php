
<?php include_once("../library/service/mypass_sent.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<script type="text/javascript">
function loan_conf(name) {
	if (document.form1.loan_date.value == ''){
		alert('※発行日を入力してください。');
		document.form1.action.value = "";
		return;
	}else if ( confirm( name + " 登録して宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}
</script>
</head>
<body> 

 

 <div class="clear"></div>
<!-- start content-outer -->
<div >
	<!-- start content -->
	<div id="content">
		
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
					<div id="content-table-inner" style="padding:0;">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td>
									<form action="../service/mypass_sent.php" method="post" name="form1" enctype="multipart/form-data" onSubmit="return loan_conf('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />

										<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
										<input type="hidden" name="start" value="<?php echo $_POST["start"];?>" />
										<input type="hidden" name="contract_date" value="<?php echo $_POST["contract_date"];?>" />
										<input type="hidden" name="contract_date2" value="<?php echo $_POST["contract_date2"];?>" />
										<input type="hidden" name="status" value="<?php echo $_POST["status"];?>" />
										<input type="hidden" name="line_max" value="<?php echo $_POST["line_max"];?>" />
										<input type="hidden" name="shop_id" value="<?php echo $_POST["shop_id"];?>" />
										<input type="hidden" name="hope_date" value="<?php echo $_POST["hope_date"];?>" />
										<input type="hidden" name="reservation_id" value="<?php echo $_POST["reservation_id"];?>" />
										
										<?php echo $gMsg2;?>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">

											<tr>
												<th valign="top">会員番号:</th>
												<td><?php echo $data['no'];?></td>
												
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><?php echo $data['name'];?></td>
											</tr>

											<tr>
												<th valign="top">マイパス発行済:</th>
												<td><input type="checkbox" name="pw_sent_flg" value="1" <?php if($data['pw_sent_flg']) echo "checked";?> class="form-checkbox" /></td>
											</tr> 
											<tr>
												<th valign="top">発行日:</th>
												<td><input type="input" name="pw_sent_date" value="<?php echo ($data['pw_sent_date']<>"0000-00-00" ? $data['pw_sent_date'] : "");?>" placeholder="<?php echo date("Y-m-d");?>" /></td>
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

 
</body>
</html>