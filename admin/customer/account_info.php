<?php include_once("../library/customer/account_info.php");?>
<?php include_once("../include/header_menu.html");?>

</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>個人支払情報<?php echo $gMsg;?></h1></div>
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
										<input type="hidden" name="cuntomer_id" value="<?php echo $customer["id"];?>" />
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">会員番号:</th>
												<td><input type="text" value="<?php echo $customer['no'] ;?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><input type="text" value="<?php echo $customer['name'] ;?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">スマートピット番号:</th>
												<td><input type="text" value="<?php echo $smartpit_no ;?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">銀行名:</th>
												<td><input type="text" value="三井住友銀行(009)" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">支店名:</th>
												<td><input type="text" value="<?php echo $virtual_bank["branch_name"] ?? "";?>（<?php echo $virtual_bank["branch_no"] ?? "";?>）" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">預金種別:</th>
												<td><input type="text" value="普通預金" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">口座番号:</th>
												<td><input type="text" value="<?php echo $virtual_bank["virtual_no"] ?? "";?>" class="inp-form" readonly /></td>
											</tr>
											<tr>
												<th valign="top">口座名義:</th>
												<td><input type="text" value="株式会社ヴィエリス" class="inp-form" readonly /></td>
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