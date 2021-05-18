<?php include_once("../library/sales/index.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

<style type="text/css">
#hoge {
    width:100%;
    position:absolute;
    top:160px;
    left:0;
}
#hogeInner {
    text-align: right;
    margin:0 0;
    padding: 0 23px;
}
</style>
<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_export.php";
	  document.search.submit();
	  return fales;
  	}else{
    	return false;
  	}
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			売上一覧
			<span style="margin-left:20px;"><span style="font-size:15px;">
				<a href="./total.php?mode=display&pay_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./total.php?mode=display&pay_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				店舗：<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				<select name="type" style="height:25px;" ><option value="">全区分</option><?php Reset_Select_Key( $gResType7 , $_POST['type'] );?></select>
				<select name="course_id" style="height:25px;width:150px;" ><?php Reset_Select_Key( $course_list , $_POST['course_id'] );?></select>
				<select name="option_name" style="height:25px;" ><?php Reset_Select_Key( $gOption3 , $_POST['option_name'] );?></select>
				<!--<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" />件/頁</span>-->
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='total.php';return true" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
			</span>
			<?php  }?>
		</h1>
		</form>
	</div>
	<!-- end page-heading -->
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
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">

<?php
if ( $dRtn3 ) {
	$i = 1;
	$cnt_monthly = 0;
	$cnt_pack = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//役務消化除外
		if($data['r_times'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card'] ) continue; 	

		$loan_status = Get_Table_Col("contract","loan_status"," where del_flg=0 and id=".$data['contract_id']);
		//$contract_status = Get_Table_Col("contract","status"," where del_flg=0 and id=".$data['contract_id']);
		//ローン取消の場合、ローンを０に
		/*if($data['type']==9) $data['payment']=$data['payment']-$data['payment_loan'];
		if($contract_status==5 || $data['type']==9) {
			$data['balance']=0;
			$data['payment_loan']=0;
		}*/
		//契約待ち+ローン取消の場合、売掛を０に
		/*if($new_contract_id = Get_Table_Col("contract","new_contract_id"," where del_flg=0 and id=".$data['contract_id'])){
			$new_contract_status = Get_Table_Col("contract","status"," where del_flg=0 and id=".$new_contract_id);
			if($new_contract_status==7 && $data['type']==9) $data['balance']=0;
		}*/


		if( $data['type']==9) $data['balance']=0;


		if($data['type']==1 || $data['type']==6)$total_fixed_price += $data['fixed_price']; 						// コース金額,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_discount += $data['discount']; 								// 値引き合計,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_price += $data['fixed_price'] - $data['discount']; 			//請求金額（商品金額）
		// 残金支払合計
		if($data['type']<>1 && $data['type']<>6 && $data['type']<>10){
			if( $data['payment_cash']>0)$total_payment += $data['payment_cash'] ; 	
			if( $data['payment_card']>0)$total_payment += $data['payment_card'] ; 	
			if( $data['payment_transfer']>0)$total_payment += $data['payment_transfer'] ; 	
			if( $loan_status==1 && $data['payment_loan']>0)$total_payment += $data['payment_loan'] ; 
		}											

		$total_option_price += $data['option_price'] + $data['option_transfer'] + $data['option_card']; 			// オプション金額合計
		//$total_sales += $data['payment'] + $data['option_price']+ $data['payment_coupon']; 						// 入金金額（売上合計）
		$total_sales += $data['payment_cash'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon'] + $data['option_price'] + $data['option_transfer'] + $data['option_card'];

		//if($data['type']==1){
			if($pre_no == $data['no']) $total_balance -= $pre_balance; 	//最新の売掛だけを計上処理
			$total_balance += $data['balance']; // 売掛金合計
		//}

		if($data['type']==1){
			if($course_type[$data['course_id']]) $cnt_monthly++; 		// 月額件数
			else $cnt_pack++; // パック件数
		}

		$total_cash 	+= $data['payment_cash'] + $data['option_price']; 	// 現金売上合計
		$total_card 	+= $data['payment_card']  + $data['option_card']; 							// カード売上合計
		$total_transfer += $data['payment_transfer'] + $data['option_transfer']  ; 						// 銀行振込合計
		$total_loan 	+= $data['payment_loan'] ; 							// ローン売上合計
		$total_coupon 	+= $data['payment_coupon'] ; 						// クーポン売上合計

		//月額退会合計
		if($data['type']==5 && $course_type[$data['course_id']] ) {
			$total[20] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}else{
			$total[$data['type']] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}

		if($data['type']==4) $cnt4++;
		if($data['type']==5 && !$course_type[$data['course_id']] ) $cnt5++; //中途解約件数
		if($data['type']==5 && $course_type[$data['course_id']] )  $cnt20++; //月額退会件数
		if($data['type']==6) $cnt6++;
		if($data['type']==7) $cnt7++;
		if($data['type']==12) $cnt12++;

		$pre_no = $data['no'];
		$pre_balance = $data['balance'];
		if($data['type']==1) $isexited_contract = true;

		//最新売掛金を格納
		$balance[$data['customer_id']] = $data['balance'];

		$i++;
	}
		
		$total_balance = array_sum($balance);
		$total_balance = $isexited_contract ? $total_balance : 0; 			// 契約データがなければ0
		$total_without_balance = $total_sales; 								// 売掛含まない総合計


		//来店件数=未契約数+契約数（月額件数+パック件数）
		$cnt_came = $dGet_Cnt4 + $cnt_monthly + $cnt_pack ;
		//カウンセリング予約件数=来店件数+来店なし件数
		$cnt_total = $cnt_came + $dGet_Cnt5 ;
		//来店率=来店件数/カウンセリング予約件数*100%
		if($cnt_total) $percent_came = round($cnt_came/$cnt_total*100)."%";
		//成約率=成約件数/来店件数*100%
		if($cnt_came) $percent_contract = round(($cnt_monthly + $cnt_pack)/$cnt_came*100)."%";

}
?>
			 <?php
		$param  = '?cnt_monthly='.$cnt_monthly.'&cnt_pack='.$cnt_pack;
		$param .= '&total_cash='.$total_cash.'&total_card='.$total_card.'&total_transfer='.$total_transfer.'&total_loan='.$total_loan.'&total_balance='.$total_balance;
		$param .= '&total_incloude='.($total_balance + $total_without_balance).'&total_uncloude='.$total_without_balance;
		echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:18px;padding-top:50px;">売掛含まない総合計: '.number_format($total_without_balance).' 円</div>';
        ?>	
				</table>
				<!--  end product-table................................... --> 

				</form>

			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php //Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
      <div class="clear"></div>
    </div>
    <!--  end content-table-inner ............................................END  -->
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
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>