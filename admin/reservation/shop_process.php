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
	$table= 'request_items';
	$gMsg = validate();
	// 更新
	if( empty($gMsg) && $_POST['id']){
		$common_field = array(
			'pay_back',
			'edit_date'
			);
		$_POST['edit_date'] = date('Y-m-d H:i:s');
		$_POST['id'] = Update_Data($table,$common_field,$_POST['id']);
		header("Location: ./cc_request.php?request_id=".$_POST['id']);
	}
}

// 詳細取得--------------------------------------------------------------
$data= array();
if( $_REQUEST['request_id'] ) $data = Get_Table_Row("request_items"," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['request_id'])."'");
$bank= array();
if( $data['customer_id'] ) $bank = Get_Table_Row("bank"," WHERE del_flg=0 AND customer_id = '".addslashes($data['customer_id'])."'");
$if_need_attorney = $data['status']==3 ? '必要' : '必要なし';

// 必須項目確認-----------------------------------------------------------
function validate(){
	$gMsg ="";
	//if( empty($_POST['amount']) )	$gMsg  = "<br />※申込日が未入力です。";
	if($gMsg) $gMsg = "<font color='red' size='-1'>".$gMsg."</font>";
	return $gMsg;
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
										<form action="./shop_process.php" method="post" id="form1" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
											<input type="hidden" name="action" value="edit" />
											<input type="hidden" name="id" value="<?php echo $data["id"];?>" />

											<table border="0" cellpadding="0" cellspacing="0" id="id-form">
												<tr>
													<th valign="top">名前:</th>
													<td><?php echo $_REQUEST['name'];?></td>
												</tr>
												<tr>
													<th valign="top">返金金額:</th>
													<td><input name="pay_back" class="inp-form" type="text" value="<?php echo Post2Data($data['pay_back'],'pay_back');?>"/></td>
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