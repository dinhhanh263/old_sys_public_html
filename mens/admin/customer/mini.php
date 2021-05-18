<?php include_once("../library/customer/mini.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
</head>
<body>

	<!-- start content -->
	<!-- <div id="content"> -->
		<!-- <div id="content-table"> -->
			<!--  start content-table-inner -->
			<!-- <div id="content-table-inner" style="padding:0;"> -->
				<div valign="top" id="customer_mini">
					<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
						<tr>
							<th valign="top">会員番号:</th>
							<td><?php echo $data['no'];?></td>
						</tr>
						<tr>
							<th valign="top">名前:</th>
							<td><?php echo $data['name'];?><br />( <?php echo $data['name_kana'];?> )</td>
						</tr>
						<tr>
							<th valign="top">電話番号:</th>
							<td><?php echo $data['mobile'] ? $data['mobile'] : $data['tel'] ;?></td>
						</tr>
						<tr class="hr">
							<th colspan="2" valign="top">売掛金:</th>
						</tr>
						<tr>
							<td colspan="2">
								<dl class="mini_ul">
									<!-- 契約番号ごとのループ -->
									<?php foreach ($contract_p as $key => $value): ?>
									<dt class="p_id">契約番号:<span class="p_no"><?php echo $value['id']?></span></dt>
									<dd class="blance_area">￥
										<?php
											if($value['status']<>7) echo number_format($value['balance']);
											else echo "(".number_format($value['balance']).")";
										?>
									<span class="memo3">
										<?php
											if($value['status']<>0)echo ($course_type[$value['course_id']] ? "(".$gContractStatus3[$value['status']].")" : "(".$gContractStatus[$value['status']].")");
										?>
									</span>
									</dd>
									<!-- ローン支払あり && 契約区分：2.クーリングオフ、3.中途解約、6.自動解約以外  -->
									<?php if($value['payment_loan'] && $value['status']<> 2 && $value['status']<>3 && $value['status']<>6 ){?>
										<!-- ローン承認済-->
										<?php if($value['loan_status']==1){?>
										<dt class="p_loan">ローン:</dt>
										<dd class="loan_area">￥<?php echo number_format($value['payment_loan']);?>
											<span class="memo3 memo_loan">(ローン状況:<?php echo $gLoanStatus[$value['loan_status']];?>)</span>
										</dd>
										<!-- ローン承認済以外-->
										<?php }else{?>
									<dt class="p_loan">(ローン:</dt>
									<dd class="loan_area">￥<?php echo number_format($value['payment_loan']);?>)
										<span class="memo3 memo_loan">(ローン状況:<?php echo $gLoanStatus[$value['loan_status']];?>)</span>
									</dd>
										<?php }?>
										<!-- 契約待ち ※ローン状況を表示する 2017/04/26 add by shimada-->
									<?php } else if($value['status']==7) { ?>
											<span class="memo3 memo_loan">(ローン状況:<?php echo $gLoanStatus[$value['loan_status']];?>)</span>
									<?php }?>
									<?php endforeach; ?>
								</dl>
							</td>
						</tr>

						<tr class="hr">
							<th valign="top">契約履歴:</th>
							<td></td>
						</tr>
						<!-- 契約コースごとのループ -->
						<?php foreach ($contract as $key => $value): ?>
						<!--<tr>
							<th valign="top">契約状況:</th>
							<td>
								<?php echo ($course_type[$value['course_id']] ? $gContractStatus3[$value['status']] : $gContractStatus[$value['status']]);?>
							</td>
						</tr> -->
						<!-- 契約中の場合のみ、「契約中」表記の色を濃くする（それ以外は薄くする） -->
						<?php $mini_ul = ($value['status']==0) ? "mini_ul" : "mini_ul2"; ?>
							<tr class="pre_line">
								<td colspan="2">
									<dl class="<?php echo $mini_ul?>">
										<dt class="c_no">契約番号<?php echo $value['pid']?>:</dt>
										<dd class="c_status">
											<?php echo ($course_type[$value['course_id']] ? $gContractStatus3[$value['status']] : $gContractStatus[$value['status']]);?>
										</dd>

										<dt class="memo_contract_name"><?php echo $course_list[$value['course_id']];?>
											<?php if($value['contract_part']<>""){?>
												<span class="memo_parts2"><!-- 契約部位あり -->
													(
													<?php $value['contract_part'] = explode(",", $value['contract_part']);?>
													<?php foreach ($value['contract_part'] as $key => $part): ?>
														<?php
															echo $gContractParts[$part];
															if ($part <> end($value['contract_part'])) { echo ',';}
														?>
													<?php endforeach; ?>
													)
												</span>
											<?php } ?>
										</dt>
										<dd class="memo_r_times"><?php echo $value['r_times'];?>回</dd>

									</dl>
								</td>
							</tr>
						<?php endforeach; ?>

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
					<!-- 単発以外のコース 予約履歴 -->
					<div class="box_right">
						<span class="single_title">ご来店履歴</span>
						<dl>
							<!-- <span class="history_box"> -->
							<?php echo $rsv_html;?>
						</dl>
					</div>
					<!-- 単発利用履歴 -->
					<div class="box_right2">
						<span class="single_title">1回コース消化履歴</span>
						<dl>
							<?php echo $one_rsv_html;?>
						</dl>
					</div>
					<div class="btn-area">
						<a class="button register_btn" href="../sales/register.php?customer_id=<?php echo $data['id']; ?>" target="_blank">物販レジへ</a>
					</div>
				</div>
			<!-- </div> -->
		<!-- </div> -->
		<!-- <div class="clear">&nbsp;</div> -->
	<!-- </div> -->
	<!--  end content -->
<!--  end content-outer -->


</body>
</html>