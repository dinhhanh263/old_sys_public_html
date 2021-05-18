<?php include_once("../library/account/index.php");?>
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
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			売上一覧
			<span style="margin-left:20px;"><span style="font-size:15px;">
			<?php if(!($_POST['customer_id'] && !$_POST['keyword'])){?>
				<!--<a href="./index.php?mode=display&pay_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./index.php?mode=display&pay_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>-->
			<?php } ?>
				店舗：<select id="shop_id" name="shop_id" style="height:25px;width:150px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<select name="type" style="height:25px;width:100px;" ><option value="">全区分</option><?php Reset_Select_Key( $gResType7 , $_POST['type'] );?></select>
				<select name="course_id" style="height:25px;width:180px;" ><?php Reset_Select_Key( $course_list , $_POST['course_id'] );?></select>
				<select name="option_name" style="height:25px;width:110px;" ><?php Reset_Select_Key( $gOption3 , $_POST['option_name'] );?></select>
				<select name="is_loan_only" style="height:25px;width:100px;" ><?php Reset_Select_Key( $gIsLoanOnly , $_POST['is_loan_only'] );?></select>
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:20px;" />
			</span>
		</h1>
		</form>
	</div>
	<!-- end page-heading -->
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">区分</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">日付</font></a>	</th>

					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">顧客氏名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">コース</font></a></th>						<!--新規売上/新規客数。店販追加予定-->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">請求金額</font></a></th>					<!--レジへの遷移-->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">値引き</font></font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ｵﾌﾟｼｮﾝ名</font></font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ｵﾌﾟｼｮﾝ金額</font></a></th>

					<th class="table-header-repeat line-left minwidth-1" title="ｵﾌﾟｼｮﾝ金額込"><a href=""><font size="-2">現金入金</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="ｵﾌﾟｼｮﾝ金額込"><a href=""><font size="-2">カード入金</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="ｵﾌﾟｼｮﾝ金額込"><a href=""><font size="-2">銀行振込</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="既払金込"><a href=""><font size="-2">ﾛｰﾝ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="クーポン入金"><a href=""><font size="-2">CP</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">売掛金</font></a></th>

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	$cnt_monthly = 0;
	$cnt_pack = 0;
	$cnt_product = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		// 役務消化除外
		if($data['r_times'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card'] ) continue;

		// $loan_status = Get_Table_Col("contract","loan_status"," where del_flg=0 and id=".$data['contract_id']);
		$loan_status = $data['loan_status'];
		
		if( $data['type']==9) $data['balance']=0;

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		if($data['type']<3 || $data['type']==51){
		//echo 	'<td>'.$gResType3[$data['type']]. '</td>';
		echo 	'<td><span onmouseover=\'this.innerText="'. $data['id']. '"\' onmouseout=\'this.innerText="'. $gResType3[$data['type']]. '"\'>'. $gResType3[$data['type']]. '</span></td>';
		}else{
		echo 	'<td><font color="red"><span onmouseover=\'this.innerText="'. $data['id']. '"\' onmouseout=\'this.innerText="'. (($data['type']==6 && $data['conversion_flg'] == 1) ? "プラン組替" : $gResType3[$data['type']]). '"\'>'. (($data['type']==6 && $data['conversion_flg'] == 1) ? "プラン組替" : $gResType3[$data['type']]). '</span></font></td>';
		}
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['pay_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.( $data['name'] ? $data['name'] : $data['name_kana']).'</td>';
		echo 	'<td>'.( $data['type']==51 ? "-" : $course_list[$data['course_id']]).'</td>'; //コース
		echo 	'<td class="priceFormat">'.( $data['type']==51 ? "-" : (($data['type']==4 || $data['type']==5 || $data['type']==9 || $data['type']==12) ? 0 : number_format($data['price'])) ).'</td>'; // 請求金額（商品金額）
		echo 	'<td class="priceFormat">'.($data['type']==1 ? number_format($data['discount']) : "").'</td>';
		echo 	'<td>'.( $data['type']==51 ? "物販" : $gOption2[$data['option_name']]).'</td>'; 					// ｵﾌﾟｼｮﾝ名
		echo 	'<td class="priceFormat">'.number_format($data['option_price'] + $data['option_transfer'] + $data['option_card']).'</td>'; 	//ｵﾌﾟｼｮﾝ金額
		echo 	'<td class="priceFormat">'.number_format($data['payment_cash'] + $data['option_price']).'</td>'; 							// 現金入金
		echo 	'<td class="priceFormat">'.number_format($data['payment_card'] + $data['option_card']).'</td>'; 							// カード入金
		echo 	'<td class="priceFormat">'.number_format($data['payment_transfer']+ $data['option_transfer']).'</td>'; 	// 銀行振込
		echo 	'<td class="priceFormat">'.number_format($data['payment_loan']).'</td>'; 								// ﾛｰﾝ
		echo 	'<td class="priceFormat">'.number_format($data['payment_coupon']).'</td>'; 								// CP
		echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>'; 									// 売掛金
		echo '</tr>';

		if($data['type']==1 || $data['type']==6)$total_fixed_price += $data['fixed_price']; 						// コース金額,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_discount += $data['discount']; 								// 値引き合計,契約時のカウンセリング時だけ
		if($data['type']==1 || $data['type']==6)$total_price += $data['fixed_price'] - $data['discount']; 			// 請求金額（商品金額）
		// 残金支払合計
		if($data['type']<>1 && $data['type']<>6 && $data['type']<>10){
			if( $data['payment_cash']>0)$total_payment += $data['payment_cash'] ;
			if( $data['payment_card']>0)$total_payment += $data['payment_card'] ;
			if( $data['payment_transfer']>0)$total_payment += $data['payment_transfer'] ;
			if( $loan_status==1 && $data['payment_loan']>0)$total_payment += $data['payment_loan'] ;

		}

		$total_option_price += $data['option_price'] + $data['option_transfer'] + $data['option_card']; 			// オプション金額合計
		// 入金金額（売上合計）
		$total_sales += $data['payment_cash'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon'] + $data['option_price'] + $data['option_transfer'] + $data['option_card'];

		// 最新の売上だけを計上処理
		if($pre_no == $data['no']) $total_balance -= $pre_balance; 
		// 売掛金合計	
		$total_balance += $data['balance']; 

		if($data['type']==1){
			if($course_type[$data['course_id']]) $cnt_monthly++; // 月額件数				
			else $cnt_pack++; // パック件数
		}

		$total_cash 	+= $data['payment_cash'] + $data['option_price']; 			// 現金売上合計
		$total_card 	+= $data['payment_card']  + $data['option_card']; 			// カード売上合計
		$total_transfer += $data['payment_transfer'] + $data['option_transfer']  ; 	// 銀行振込合計
		$total_loan 	+= $data['payment_loan'] ; 									// ローン売上合計
		$total_coupon 	+= $data['payment_coupon'] ; 								// クーポン売上合計

		// 月額退会合計
		if($data['type']==5 && $course_type[$data['course_id']] ) {
			$total[20] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}else{
			$total[$data['type']] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
		}

		if($data['type']==4) $cnt4++;
		if($data['type']==5 && !$course_type[$data['course_id']] ) $cnt5++; // 中途解約件数
		if($data['type']==5 && $course_type[$data['course_id']] )  $cnt20++; // 月額退会件数
		if($data['type']==6) $cnt6++;
		if($data['type']==7) $cnt7++;
		if($data['type']==12) $cnt12++;
		if($data['type']==51){$cnt51_noprice += ($data['option_price']==0 && $data['option_card']==0 ? 1 : 0);$total_cnt51 += ($data['option_price'] + $data['option_card']);$cnt_product++;}//物販出庫数、プレゼント数、物販のみの売上、物販購入者数

		$pre_no = $data['no'];
		$pre_balance = $data['balance'];
		$isexited_contract = true; // 売掛がある場合はtrue 20160420 区分フィルタをかけると売掛金が0円になるためコメントアウトshimada

		//最新売掛金を格納
		$balance[$data['customer_id']] = $data['balance'];

		$i++;
	}
		if(is_array($balance)){
			$total_balance = array_sum($balance);
		}else{
			$total_balance = $balance;
		}
		$total_balance = $isexited_contract ? $total_balance : 0; 			// 契約データがなければ0
		$total_without_balance = $total_sales; 								// 売掛含まない総合計
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>合計</td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td class="priceFormat">'.number_format($total_option_price).'</td>'; 	// ｵﾌﾟｼｮﾝ金額
		echo 	'<td class="priceFormat">'.number_format($total_cash).'</td>';			// 現金入金
		echo 	'<td class="priceFormat">'.number_format($total_card).'</td>';			// カード入金
		echo 	'<td class="priceFormat">'.number_format($total_transfer).'</td>'; 		// 銀行振込
		echo 	'<td class="priceFormat">'.number_format($total_loan).'</td>'; 			// ローン
		echo 	'<td class="priceFormat">'.number_format($total_coupon).'</td>';		// CP
		echo 	'<td class="priceFormat">'.number_format($total_balance).'</td>';		// 売掛金
		echo '</tr>';
	//顧客別の場合非表示
	if(!$_POST['customer_id'] && !$_POST['keyword'])	{
		echo '<tr><td colspan="15"></td><td>内訳</td>';
		echo '<tr><td colspan="15" class="priceFormat">月額:</td><td class="priceFormat">'.number_format($cnt_monthly).' 名</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">パック:</td><td class="priceFormat">'.number_format($cnt_pack).' 名</td></tr>';
		// echo '<tr><td colspan="15" class="priceFormat">未契約:</td><td class="priceFormat">'.number_format($dGet_Cnt4).' 名</td></tr>';

		//来店件数=未契約数+契約数（月額件数+パック件数）
		//$cnt_came = $dGet_Cnt4 + $cnt_monthly + $cnt_pack ;
		//カウンセリング予約件数=来店件数+来店なし件数
		//$cnt_total = $cnt_came + $dGet_Cnt5 ;
		//来店率=来店件数/カウンセリング予約件数*100%
		//if($cnt_total) $percent_came = round($cnt_came/$cnt_total*100)."%";
		//成約率=成約件数/来店件数*100%
		//if($cnt_came) $percent_contract = round(($cnt_monthly + $cnt_pack)/$cnt_came*100)."%";

		// echo '<tr><td colspan="15" class="priceFormat">来店率:</td><td class="priceFormat">'.$percent_came.'</td></tr>';
		// echo '<tr><td colspan="15" class="priceFormat">成約率:</td><td class="priceFormat">'.$percent_contract.'</td></tr>';

	if(isset($total['4'])){
		echo '<tr><td colspan="15" class="priceFormat">クーリングオフ:</td><td class="priceFormat">'.number_format($cnt4).' 名</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">クーリングオフ返金:</td><td class="priceFormat">'.number_format($total['4']).' 円</td></tr>';
	}
	if(isset($total['5'])){
		echo '<tr><td colspan="15" class="priceFormat">中途解約:</td><td class="priceFormat">'.number_format($cnt5).' 名</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">中途解約返金:</td><td class="priceFormat">'.number_format($total['5']).' 円</td></tr>';
	}
	if(isset($total['20'])){
		echo '<tr><td colspan="15" class="priceFormat">月額退会:</td><td class="priceFormat">'.number_format($cnt20).' 名</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">月額退会返金:</td><td class="priceFormat">'.number_format($total['20']).' 円</td></tr>';
	}
	if(isset($total['6'])){
		echo '<tr><td colspan="15" class="priceFormat">プラン変更:</td><td class="priceFormat">'.number_format($cnt6).' 名</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">プラン変更差額:</td><td class="priceFormat">'.number_format($total['6']).' 円</td></tr>';
	}
	if(isset($cnt12)){
		echo '<tr><td colspan="15" class="priceFormat">自動解約:</td><td class="priceFormat">'.number_format($cnt12).' 名</td></tr>';
	}

		echo '<tr><td colspan="15" class="priceFormat">現金売上:</td><td class="priceFormat">'.number_format($total_cash).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">カード売上:</td><td class="priceFormat">'.number_format($total_card).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">銀行振込:</td><td class="priceFormat">'.number_format($total_transfer).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">ローン売上:</td><td class="priceFormat">'.number_format($total_loan).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">売掛回収:</td><td class="priceFormat">'.number_format($total_payment).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">売掛金:</td><td class="priceFormat">'.number_format($total_balance).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">売掛含む総合計:</td><td class="priceFormat">'.number_format($total_balance + $total_without_balance).' 円</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">売掛含まない総合計:</td><td class="priceFormat">'.number_format($total_without_balance).' 円</td></tr>';
		if(($cnt_monthly+$cnt_pack+$dGet_Cnt4)){
			echo '<tr><td colspan="15" class="priceFormat">客単価((売掛含む総合計 - 物販売上)/来店数):</td><td class="priceFormat">'.number_format(($total_balance + $total_without_balance - $total_cnt51)/($cnt_monthly+$cnt_pack+$dGet_Cnt4)).' 円</td></tr>';
		}else{
			echo '<tr><td colspan="15" class="priceFormat">客単価((売掛含む総合計 - 物販売上)/来店数):</td><td class="priceFormat">'.number_format(0).' 円</td></tr>';
		}
		echo '<tr class="line"></tr><tr><td colspan="15" class="priceFormat">物販利用者:</td><td class="priceFormat">'.number_format($cnt_product).' 人</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">物販売上:</td><td class="priceFormat">'.number_format($total_cnt51).' 円</td></tr>';
		if(($cnt_product)){
			echo '<tr><td colspan="15" class="priceFormat">物販客単価（物販売上/物販利用者）:</td><td class="priceFormat">'.number_format($total_cnt51/$cnt_product).' 円</td></tr>';
		}else{
			echo '<tr><td colspan="15" class="priceFormat">物販客単価（物販売上/物販利用者）:</td><td class="priceFormat">'.number_format(0).' 円</td></tr>';
		}
		echo '<tr><td colspan="15" class="priceFormat">（物販無料プレゼント人数）:</td><td class="priceFormat">('.number_format($cnt51_noprice).' 人)</td></tr>';
	}
}
?>

				</table>
				<!--  end product-table................................... -->
				※ 契約時（区分：カウンセリング）合計に計上する項目：コース金額、値引、請求金額（税込）、売掛金(表示期間内の最新売掛だけを計上)、月額件数、パック件数<br>
				※ 売掛含む総合計、売掛含まない総合計には物販売上が含まれます
				</form>
				<div id="hoge">
			    <div id="hogeInner">
			       <?php
					$param  = '?cnt_monthly='.$cnt_monthly.'&cnt_pack='.$cnt_pack;
					$param .= '&total_cash='.$total_cash.'&total_card='.$total_card.'&total_transfer='.$total_transfer.'&total_loan='.$total_loan.'&total_balance='.$total_balance;
					$param .= '&total_incloude='.($total_balance + $total_without_balance).'&total_uncloude='.$total_without_balance;
					echo '<a class="side" rel="facebox" href="../sales/report.php'.$param.'">売掛含まない総合計: '.number_format($total_without_balance).' 円</a>';
			       ?>
			    </div>
				</div>
			</div>
			<!--  end content-table  -->
    </div>
    <!--  end content-table-inner ............................................END  -->
    </td>
  </tr>
  </table>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>
