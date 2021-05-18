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
			'pay_back_cc',
			'last_visit_ym',
			'transfer_date',
			'process_approval',
			'process_detail',
			'process_status',
			'process_memo',
			'transfer_commission',
			'amount',
			'loan_respond',
			'loan_request_status',
			'edit_date'
			);
		$_POST['edit_date'] = date('Y-m-d H:i:s');
		if($_POST['process_status']==3){
			$_POST['end_flg'] = 1;
			$_POST['end_date'] = date('Y-m-d H:i:s');
			array_push($common_field, 'end_flg','end_date');
			$_POST['id'] = Update_Data($table,$common_field,$_POST['id']);
			header("Location: ./cc_request.php?keyword=".$_POST['keyword']);
		}else{
			$_POST['id'] = Update_Data($table,$common_field,$_POST['id']);
			header("Location: ./cc_request.php?request_id=".$_POST['id']);
		}
	}
}
?>
<!-- 月のDATEPICKER を読み込むためのJS -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>
<?php 
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
										<form action="./cc_process.php" method="post" id="form1" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
											<input type="hidden" name="action" value="edit" />
											<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
											<input type="hidden" name="keyword" value="<?php echo $_REQUEST["no"];?>" />

											<table border="0" cellpadding="0" cellspacing="0" id="id-form">
												<!--<tr>
													<th valign="top">会員番号:</th>
													<td><?php echo $_REQUEST['no'];?></td>
												</tr>-->
												<tr>
													<th valign="top">名前:</th>
													<td><?php echo $_REQUEST['name'];?></td>
												</tr>
												<tr>
													<th valign="top">返金日・入金日:</th>
													<td><input name="transfer_date" class="inp-form" type="tel" value="<?php echo Post2Data($data['transfer_date'],'transfer_date');?>" placeholder="2018-01-01" id="fm" /></td>
												</tr>
												<tr>
													<th valign="top">経理処理（承認）:</th>
													<td><select name="process_approval" class="styledselect_form_3"><?php Reset_Select_Key( $gProcessApproval , Post2Data($data['process_approval'],'process_approval'));?></select></td>
												</tr>
												<tr>
													<th valign="top">経理備考:</th>
													<td><textarea name="process_detail" class="form-textarea2"><?php echo Post2Data($data['process_detail'],'process_detail');?></textarea></td>
												</tr>
												<tr>
													<th valign="top">経理処理ステータス:</th>
													<td><select name="process_status" class="styledselect_form_3"><?php Reset_Select_Key( $gProcessStatus , Post2Data($data['process_status'],'process_status'));?></select></td>
												</tr>
												<tr>
													<th valign="top">備考:</th>
													<td><textarea name="process_memo" class="form-textarea2"><?php echo Post2Data($data['process_memo'],'process_memo');?></textarea></td>
												</tr>
												<tr>
													<th valign="top">返金額（CC）:</th>
													<td><input class="inp-form" name="pay_back_cc" type="text" value="<?php echo Post2Data($data['pay_back_cc'],'pay_back_cc');?>" /></td>
												</tr>
												<tr>
													<th valign="top">振込手数料:</th>
													<td><input class="inp-form" name="transfer_commission" type="text" value="<?php echo ($data['edit_date']=="0000-00-00 00:00:00") ? 880 : (Post2Data($data['transfer_commission'],'transfer_commission') ? Post2Data($data['transfer_commission'],'transfer_commission') : $data['transfer_commission']) ;?>" /></td>
												</tr>
												<tr>
													<th valign="top">対応金額:</th>
													<td><input name="amount" class="inp-form" type="text" value="<?php echo Post2Data($data['amount'],'amount');?>" /></td>
												</tr>
												<tr>
													<th valign="top">振込手数料引いた金額:</th>
													<td><input class="inp-form" type="text" value="<?php echo (Post2Data($data['amount'],'amount')+Post2Data($data['transfer_commission'],'transfer_commission'));?>" readonly /></td>
												</tr>
												<tr>
													<th valign="top">ローン部署対応:</th>
													<td><select name="loan_respond" name="type" class="styledselect_form_3"><?php Reset_Select_Key( $gLoanRespond , Post2Data($data['loan_respond'],'loan_respond'));?></select></td>
												</tr>
												<tr>
													<th valign="top">ローン会社依頼状態:</th>
													<td><select name="loan_request_status" name="type" class="styledselect_form_3"><?php Reset_Select_Key( $gLoanRequestStatus , Post2Data($data['loan_request_status'],'loan_request_status'));?></select></td>
												</tr>
												<tr>
													<th valign="top">委任状必要:</th>
													<td><?php echo $if_need_attorney;?></td>
												</tr>
												<tr>
													<th valign="top">委任状:</th>
													<td><?php echo $gAttorneyStatus[$_REQUEST['attorney_status']];?></td>
												</tr>
												<?php if($data["type"]==1 && $data["status"]==1){ ?>
												<tr>
													<th valign="top">(月額)最終来店月:</th>
													<td><input style="width:55px;height:21px;" name="last_visit_ym" type="text" id="last_visit_ym" class="ympicker" value="<?php echo Post2Data($data['last_visit_ym'],'last_visit_ym');?>" /></td>
												</tr>
												<?php if($_REQUEST['last_visit_ym']){ ?>
												<tr>
													<th valign="top">(月額)最終引落月:</th>
													<td><?php echo date('Y/m',strtotime('-2 month',strtotime($_REQUEST['last_visit_ym'].'/1')));?></td>
												</tr>
												<?php }} ?>
												<tr>
													<th valign="top">===========</th><td>口座情報</td>
												</tr>
									
												<tr>
													<th valign="top">銀行名：</th>
													<td><?php echo $bank['bank_name'];?></td>
												</tr>
												<tr>
													<th valign="top">支店名：</th>
													<td><?php echo $bank['bank_branch'];?></td>
												</tr>
												<tr>
													<th valign="top">口座種別：</th>
													<td><?php echo $gBankType[$bank['bank_account_type']];?></td>
												</tr>
												<tr>
													<th valign="top">口座番号：</th>
													<td><?php echo $bank['bank_account_no'];?></td>
												</tr>
												<tr>
													<th valign="top">口座名義：</th>
													<td><?php echo mb_convert_kana($bank['bank_account_name'],'askh');?></td>
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