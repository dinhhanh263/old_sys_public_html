<?php include_once("../library/reservation/pay_monthly.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>

<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="../js/main.js"></script>



</head>
<body> 


<!-- start content-outer ........................................................................................................................START -->
<div >
<!-- start content -->
<div>

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">

	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content">
			
				<!--  start product-table ..................................................................................... -->
					
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<form name="search" method="post" action="">
					<input type="hidden" name="mode" value="reg" />
					<input type="hidden" name="id" value="<?php echo $_POST["id"];?>" />
					<input type="hidden" name="customer_id" value="<?php echo $_POST["customer_id"];?>" />
					<input type="hidden" name="contract_id" value="<?php echo $_POST["contract_id"];?>" />
 					<tr><td colspan="2">登録店舗</td><td colspan="22"><select name="pay_shop" style="height:20px;" ><?php Reset_Select_Key( $shop_list , $_POST['pay_shop'] ? $_POST['pay_shop'] : $contract['pay_shop']);?></select></td></tr>
 					<tr><td colspan="2">登録日付</td><td colspan="22"><input  style="height:20px;" name="pay_reg_date" type="text" value="<?php echo $_POST['pay_reg_date'] ? $_POST['pay_reg_date'] : ($contract['pay_shop'] && $contract['pay_reg_date']<>"0000-00-00" ? $contract['pay_reg_date'] : "");?>" placeholder="<?php echo date("Y-m-d")?> "/></td></tr>
					<tr><td colspan="2">支払方法</td><td colspan="22" style="height:20px;"><?php echo InputRadioTag("pay_type",$gPayType3 ,$contract['pay_type']);?></td></tr>
					<tr><td colspan="2">カード種類</td><td colspan="22"><select name="card_type" style="height:20px;" ><?php Reset_Select_Key( $gCardType , $_POST['card_type'] ? $_POST['card_type'] : $contract['card_type']);?></select></td></tr>
					<tr><td colspan="2">カード名義</td><td colspan="22"><input  style="height:20px;" name="card_name_kana" type="text" value="<?php echo $customer['card_name_kana'];?>" /></td></tr>
					<tr><td colspan="2">カード名義(ローマ字)</td><td colspan="22"><input  style="height:20px;" name="card_name" type="text" value="<?php echo $customer['card_name'];?>" /></td></tr>
					<tr><td colspan="2">カード下4桁</td><td colspan="22"><input  style="height:20px;" name="card_no" type="text" value="<?php echo $customer['card_no'];?>" /></td></tr>
					<tr><td colspan="2">カード有効期限(月/年)</td><td colspan="22"><input  style="height:20px;width:70px" placeholder="06" name="card_limit_month" type="text" value="<?php echo $_POST["card_limit_month"] ? $_POST["card_limit_month"] : $customer["card_limit_month"];?>" />/<input  style="height:20px;width:85px" placeholder="20" name="card_limit_year" type="text" value="<?php echo $_POST["card_limit_year"] ? $_POST["card_limit_year"] : $customer["card_limit_year"];?>" /><?php echo $card_error_msg;?></td></tr>
					<tr><td colspan="22" align="center"><input type="submit" value=" 支払情報登録 "  style="height:20px;" /></td></tr>
				</form>
				<tr><td colspan="22" align="center" ><?php echo $pay_monthly;?></td></tr>
				<tr><td colspan="22" align="center" class="pay_monthly_error"><?php echo $pay_error_msg;?></td></tr>
				<tr><td colspan="22" align="center" >月額支払一覧</td></tr>
				<tr><td>振替日</td><td>金額</td><td>支払方法</td><td>何年</td><td>何月分</td><td>結果</td><td>処理日</td></tr>
<?php if ( $list ) {
	$i = 1;//横ライン
	foreach($list as $key => $val){
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$val['option_date'].'</td>';
		
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
				<!--  end product-table................................... --> 

			</div>
			<!--  end content-table  -->

		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>

	</tr>

	</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
