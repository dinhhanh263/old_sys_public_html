<?php include_once("../library/customer/mini.php");?>
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
											<tr>
												<th valign="top">会員番号:</th>
												<td><?php echo $data['no'];?></td>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><?php echo $data['name'];?></td>
											</tr>
											<tr>
												<th valign="top">電話番号:</th>
												<td><?php echo $data['mobile'] ? $data['mobile'] : $data['tel'] ;?></td>
											</tr>

											<tr>
												<th valign="top">契約状況:</th>
												<td><?php echo ($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]);?></td>
											</tr>

											<tr>
												<th valign="top">契約コース:</th>
												<td><?php echo ( $course_list[$contract['course_id']]);?></td>
											</tr>
											<tr>
												<th valign="top">消化回数:</th>
												<td><?php echo $contract['r_times'];?></td>
											</tr>
											<?php if($contract['payment_loan'] && $contract['status']<> 2 && $contract['status']<>3 && $contract['status']<>6 ){?>
											<tr>
												<th valign="top">ローン:</th>
												<td>￥<?php echo number_format($contract['payment_loan']);?><br>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</td>
											</tr>
											<?php }?>
											<tr>
												<th valign="top">売掛:</th>
												<td>￥
													<?php
														if($contract['status']<>7) echo number_format($contract['balance']);
														else echo "(".number_format($contract['balance']).")";
													?>
												</td>
											</tr>
											<?php if($contract['shop_id']){?>
											<tr>
												<th valign="top">契約店舗:</th>
												<td><?php echo $shop_list[$contract['shop_id']];?></select></td>
											</tr>
											<?php }else{?>
											<tr>
												<th valign="top">申込店舗:</th>
												<td><?php echo $shop_list[$data['shop_id']];?></select></td>
											</tr>
											<?php }?>
											<tr>
												<th valign="top">備考:</th>
												<td><?php echo TA_Cook($data['memo']) ;?></td>
											</tr>
										</table>
								</td>
								<td>
									<!-- <th rowspan="20" style="vertical-align:top;padding-left:150px;width:300px"> -->
										<div class="box_right"><?php echo $rsv_html;?></div>
										<div class="btn-area">
											<a class="button register_btn" href="../sales/register.php?customer_id=<?php echo $data['id']; ?>" target="_blank">物販レジへ</a>
										</div>
									<!-- </th> -->
								</td>
							</tr>
							<!-- <tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr> -->
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