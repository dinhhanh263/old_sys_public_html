<?php include_once("../library/contract/pay_monthly.php");?>
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
					<div id="content-table-inner" style="padding:0;" class="box_right4">
					<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<tr><td colspan="2">登録店舗</td><td colspan="22" name="pay_shop"  ><?php echo $shop_list[$contract['pay_shop']];?></td></tr>
					<tr><td colspan="2">登録日付</td><td colspan="22" name="pay_reg_date" ><?php echo $contract['pay_shop'] && $contract['pay_reg_date']<>"0000-00-00" ? $contract['pay_reg_date'] : "";?></td></tr>
					<tr><td colspan="2">支払方法</td><td colspan="22" ><?php echo $gPayType3[$contract['pay_type']];?></td></tr>
					<tr><td colspan="2">カード種類</td><td colspan="22" name="card_type" ><?php echo $gCardType[$contract['card_type']];?></td></tr>
					<tr><td colspan="2">カード名義</td><td colspan="22" name="card_name_kana" ><?php echo $customer['card_name_kana'];?></td></tr>
					<tr><td colspan="2">カード名義(ローマ字)</td><td colspan="22" name="card_name" ><?php echo $customer['card_name'];?></td></tr>
					<tr><td colspan="2">カード下4桁</td><td colspan="22" name="card_no" ><?php echo $customer['card_no'];?></td></tr>
					<tr><td colspan="2">カード有効期限(月/年)</td><td colspan="22" name="card_limit" ><?php echo $customer['card_limit_month'];?>/<?php echo $customer['card_limit_year']; echo $card_error_msg;?></td></tr>
					<tr><td colspan="22" align="center" ><?php echo $pay_monthly;?></td></tr>
					<?php if(count($monthly_pause)>0) { ?>
					<tr><td colspan="22" align="center" ><?php echo $monthly_pause_msg;?></td></tr>
					<?php } ?>
					<tr><td colspan="22" align="center" class="pay_monthly_error"><?php echo $pay_error_msg;?></td></tr>
					<tr><td colspan="22" align="center" >月額支払一覧</td></tr>
					<tr><td>振替日</td><td>金額</td><td>支払方法</td><td>何年</td><td>何月分</td><td>結果</td><td>処理日</td></tr>
					<?php if ( $list ) {
						$i = 1;//横ライン
						foreach($list as $key => $val){
						echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
						echo 	'<td><a href="/admin/service/detail.php?'.($val['reservation_id'] != 0 ? 'reservation_id='.$val['reservation_id'] : 'sales_id='.$key).'" target="_blank">'.$val['option_date'].'</a></td>';
		
						echo 	'<td>'.number_format($val['pay_amount']).'</td>';
 						echo 	'<td>'.$gPayType2[$val['pay_type']].'</td>';
			 			echo 	'<td>'.$val['option_year'].'年</td>';
 						echo 	'<td>'.$val['option_month'].'月分</td>';
 						echo 	'<td>支払済</td>';
 						echo 	'<td>'.$val['pay_date'].'</td>';
						echo '</tr>';
						$i++;
						}
					}?>
				
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
