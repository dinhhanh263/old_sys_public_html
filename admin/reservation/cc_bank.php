<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

$data= array();
$gAccountType = array(0=>'-',1=>'普通',2=>'当座',3=>'貯蓄');
if( $_REQUEST['customer_id'] ) {
	$data = Get_Table_Row("bank"," WHERE del_flg=0 AND customer_id = '".addslashes($_REQUEST['customer_id'])."' ORDER BY id DESC LIMIT 1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
</head>
<body>
<div class="clear"></div>
<div ><!-- start content-outer -->
	<div id="content"><!-- start content -->
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
					<div style="padding:0;"><!--  start content-table-inner -->
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td>
									<div class="box_right">
									<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
										<!--<tr>
											<th valign="top">会員番号:</th>
											<td><?php echo $_REQUEST['no'];?></td>
										</tr>-->
										<tr>
											<th valign="top">名前:</th>
											<td><?php echo $_REQUEST['name'];?></td>
										</tr>
										<tr>
											<th valign="top">銀行名:</th>
											<td><?php echo $data['bank_name'];?></td>
										</tr>
										<tr>
											<th valign="top">支店名:</th>
											<td><?php echo $data['bank_branch'];?></td>
										</tr>
										<tr>
											<th valign="top">口座種類:</th>
											<td><?php echo $gAccountType[$data['bank_account_type']];?></td>
										</tr>
										<tr>
											<th valign="top">口座番号:</th>
											<td><?php echo $data['bank_account_no'];?></td>
										</tr>
										<tr>
											<th valign="top">口座名義:</th>
											<td><?php echo $data['bank_account_name'];?></td>
										</tr>
									</table>
									</div>
								</td>
							</tr>
						</table>
 						<div class="clear"></div>
					</div><!--  end content-table-inner  -->
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
	</div><!--  end content -->
	<div class="clear">&nbsp;</div>
</div><!--  end content-outer -->
</body>
</html>