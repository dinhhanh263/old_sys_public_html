<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
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
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">

											<!-- <tr>
												<th valign="top">月額:</th>
												<td align="right"><?php echo number_format($_GET['cnt_monthly']);?> 件</td>
											</tr> -->
											<tr>
												<th valign="top">契約者数:</th>
												<td align="right"><?php echo number_format($_GET['cnt_pack']);?> 件</td>
											</tr>
											<tr>
												<th valign="top">現金売上:</th>
												<td align="right"><?php echo number_format($_GET['total_cash']) ;?> 円</td>
											</tr> 

											<tr>
												<th valign="top">カード売上:</th>
												<td align="right"><?php echo number_format($_GET['total_card']) ;?> 円</td>
											</tr> 

											<tr>
												<th valign="top">銀行振込:</th>
												<td align="right"><?php echo number_format($_GET['total_transfer']) ;?> 円</td>
											</tr> 
											<tr>
												<th valign="top">ローン売上:</th>
												<td align="right"><?php echo number_format($_GET['total_loan']) ;?> 円</td>
											</tr> 
											<tr>
												<th valign="top">売掛:</th>
												<td align="right"><?php echo number_format($_GET['total_cash']) ;?> 円</td>
											</tr>
											<tr>
												<th valign="top">売掛金:</th>
												<td align="right"><?php echo number_format($_GET['total_balance']) ;?> 円</td>
											</tr>
											<tr>
												<th valign="top">売掛含む総合計:</th>
												<td align="right"><?php echo number_format($_GET['total_incloude']) ;?> 円</td>
											</tr>
											<tr>
												<th valign="top">売掛含まない総合計:</th>
												<td align="right"><?php echo number_format($_GET['total_uncloude']) ;?> 円</td>
											</tr>
											
										</table>
							
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