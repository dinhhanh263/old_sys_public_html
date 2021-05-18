<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

// 新規・編集-------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	$_POST['edit_date'] = date("Y-m-d H:i:s");
	// 更新
	if( $_POST['customer_memo_id'] ){
		$common_field = array(
			"memo_shop",
			"memo_cc",
			"memo_loan",
			"memo_head_office",
			"edit_date",
			);
		Update_Data("customer_memo",$common_field,$_POST['customer_memo_id']);
		header("Location: ./index.php?customer_id=".$_POST['customer_id']);
	}
}

// 詳細取得--------------------------------------------------------------
$data= array();
if( $_POST['customer_id'] ) $data = Get_Table_Row("customer_memo"," WHERE del_flg=0 AND customer_id = '".addslashes($_REQUEST['customer_id'])."'");

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
									<div class="box_right3">
										<form action="./memo_mini.php" method="post" id="form1" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
											<input type="hidden" name="action" value="edit" />
											<input type="hidden" name="customer_id" value="<?php echo $data["customer_id"];?>" />
											<input type="hidden" name="customer_memo_id" value="<?php echo $data["id"];?>" />

											<table border="0" cellpadding="0" cellspacing="0" id="id-form">
												<tr>
													<th valign="top">備考(店舗用):</th>
													<td><textarea name="memo_shop" class="form-textarea4"><?php echo TA_Cook($data['memo_shop'] ? $data['memo_shop'] : $_POST['memo_shop']) ;?></textarea></td>
												</tr>
												<tr>
													<th valign="top">備考(CC用):</th>
											  		<?php if($authority_level<=6){ ?>
															<td><textarea name="memo_cc" class="form-textarea4" ><?php echo TA_Cook($data['memo_cc'] ? $data['memo_cc'] : $_POST['memo_cc']) ;?></textarea></td>
													<?php }else{?>
															<td><textarea class="form-textarea4" disabled ><?php echo TA_Cook($data['memo_cc'] ? $data['memo_cc'] : $_POST['memo_cc']) ;?></textarea></td>
															<input type="hidden" name="memo_cc" value="<?php echo TA_Cook($data['memo_cc'] ? $data['memo_cc'] : $_POST['memo_cc']) ;?>" />
											  		<?php } ?>
												</tr>
												<tr>
													<th valign="top">備考(ローン関連):</th>
											  		<?php if($authority_level<=6){ ?>
															<td><textarea name="memo_loan" class="form-textarea4" ><?php echo TA_Cook($data['memo_loan'] ? $data['memo_loan'] : $_POST['memo_loan']) ;?></textarea></td>
													<?php }else{?>
															<td><textarea class="form-textarea4" disabled ><?php echo TA_Cook($data['memo_loan'] ? $data['memo_loan'] : $_POST['memo_loan']) ;?></textarea></td>
															<input type="hidden" name="memo_loan" value="<?php echo TA_Cook($data['memo_loan'] ? $data['memo_loan'] : $_POST['memo_loan']) ;?>" />
											  		<?php } ?>
												</tr>
												<tr>
													<th valign="top">備考(本社用):</th>
											  		<?php if($authority_level<=6){ ?>
															<td><textarea name="memo_head_office" class="form-textarea4" ><?php echo TA_Cook($data['memo_head_office'] ? $data['memo_head_office'] : $_POST['memo_head_office']) ;?></textarea></td>
													<?php }else{?>
															<td><textarea class="form-textarea4" disabled ><?php echo TA_Cook($data['memo_head_office'] ? $data['memo_head_office'] : $_POST['memo_head_office']) ;?></textarea></td>
															<input type="hidden" name="memo_head_office" value="<?php echo TA_Cook($data['memo_head_office'] ? $data['memo_head_office'] : $_POST['memo_head_office']) ;?>" />
											  		<?php } ?>
												</tr>
													<th>&nbsp;</th>
													<td valign="top">
														<input type="submit" value="" class="form-submit" />
														<input type="reset" value="" class="form-reset" />
													</td>
												</tr>
											</table>
										</form>
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