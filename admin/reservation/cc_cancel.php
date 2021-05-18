<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

$loan_company_list = getDatalist("loan_company");
$course_list = getDatalist("course");
$course_sql =  $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_type[$result['id']] = $result['type'];
}

$data= array();
$contract= array();
if( $_REQUEST['contract_id'] ) {
	$contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['contract_id'])."'");
	$data = Get_Table_Row("sales"," WHERE del_flg=0 AND type=5 AND contract_id = '".addslashes($_REQUEST['contract_id'])."' ORDER BY id DESC LIMIT 1");
}

if($contract['sales_id'] != 0 ) {
	if($data['id']){
		$payed_price = $data['fixed_price'] - $data['discount'] - $data['price'];
	}else{
		// 支払済金額
		$payed_price = $contract['fixed_price'] - $contract['discount'] - $contract['balance'];
	}
}else{
	// 支払済金額
	$payed_price =$contract['fixed_price'] - $contract['discount'] - $contract['balance'];
}

// 消化単価
$per_price = $contract['times'] ? round(($contract['fixed_price']-$contract['discount'])/$contract['times']) : 0;

// 消化金額
$usered_price = $per_price * $contract['r_times'];

// 月額が0
if($course_type[$contract['course_id']]) $remained_price =0;
else{
	if($data['id'])$remained_price = -$data['payment'];
	// 残金
	else $remained_price = $payed_price - $usered_price;
}

// 手数料：返金の10％、最大2万円,月額が手数料なし
if($course_type[$contract['course_id']]) $charge = 0;
elseif($contract['sales_id']) $charge = $data['charge'];
else{
	// 値引き後基準
	$charge = round(($contract['fixed_price'] - $contract['discount'] - $usered_price)*0.1);
	if($charge > 20000) $charge = 20000;
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
											<th valign="top">コース:</th>
											<td><?php echo $course_list[$contract['course_id']];?></td>
										</tr>
										<tr>
											<th valign="top">コース金額（税込）:</th>
											<td><?php echo number_format($contract['fixed_price']);?></td>
										</tr>
										<tr>
											<th valign="top">値引き:</th>
											<td><?php echo number_format($contract['discount']);?></td>
										</tr>
										<tr>
											<th valign="top">商品金額（税込）:</th>
											<td><?php echo number_format($contract['fixed_price']-$contract['discount']);?></td>
										</tr>
										<tr>
											<th valign="top">売掛金:</th>
											<td><?php echo number_format($data['id'] ? ($data['price']- $data['balance']) : $contract['balance']);?></td>
										</tr>
										<tr>
											<th valign="top">消化回数:</th>
											<td><?php echo ($contract['r_times']);?></td>
										</tr>
										<tr>
											<th valign="top">消化単価:</th>
											<td><?php echo number_format($per_price);?></td>
										</tr>
										<tr>
											<th valign="top">消化金額:</th>
											<td><?php echo number_format($usered_price);?></td>
										</tr>
										<tr>
											<th valign="top">既払金(ローン):</th>
											<td><?php echo number_format($data['payment_loan']);?></td>
										</tr>
										<tr>
											<th valign="top">残金:</th>
											<td><?php echo number_format(0-$remained_price);?></td>
										</tr>
										<tr>
											<th valign="top">解約手数料:</th>
											<td><?php echo number_format($charge);?></td>
										</tr>
										<tr>
											<th valign="top">ローン分割手数料:</th>
											<td><?php echo number_format($data['charge2']) ;?></td>
										</tr>
										<tr>
											<th valign="top">ローンキャンセル手数料:</th>
											<td><?php echo number_format($data['charge3'])  ;?></td>
										</tr>
										<tr>
											<th valign="top">返金額(現金):</th>
											<td><?php echo number_format($data['payment_cash']+$data['option_price']) ;?></td>
										</tr>
										<tr>
											<th valign="top">返金額(カード):</th>
											<td><?php echo number_format($data['payment_card']+$data['option_card']) ;?></td>
										</tr>
										<tr>
											<th valign="top">返金額(振込):</th>
											<td><?php echo number_format($data['id'] ? ($data['payment_transfer']+$data['option_transfer']) : ($charge-$remained_price)) ;?></td>
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