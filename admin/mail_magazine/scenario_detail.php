<?php include_once('../library/mail_magazine/scenario_detail.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>シナリオ　詳細</h1></div>
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

<?php if($gScenarioList){
	foreach($gScenarioList as $key => $val){
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">' . $val['name'] . '</td>';
		print '<td width="80%">';
		switch ( $val['type'] ){
			case 'none':
				print TA_Cook($data[$key]) . '<input name="' . $key . '" type="hidden" value="' . $data[$key] . '">';break;
			case 'text':
				print '<input name="' . $key . '" class="imeon"  value="' . TA_Cook($data[$key]) . '" size="'.$val['size'].'" >';break;
			case 'select':
				print '<select name="' . $key . '">';
				Reset_Select_Key( $val['param'] ,  $data[$key] );
				print '</select>';
				break;
			case 'textarea':
				print '<textarea name="' . $key . '" rows="' . $val['rows'] . '" cols="' . $val['cols'] . '">' .  $data[$key]  . '</textarea>';break;
		}	
		print '</td>';
		print '</tr>';
	}
}	
?>
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