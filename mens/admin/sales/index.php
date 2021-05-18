<?php include_once("../library/sales/index.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
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
			<?php if(!$_POST['customer_id']){?>
				<a href="./index.php?mode=display&pay_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./index.php?mode=display&pay_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<?php } ?>

				店舗：<select name="shop_id" style="height:25px;width:150px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				<select name="type" style="height:25px;width:100px;" ><option value="">全区分</option><?php Reset_Select_Key( $gResType8 , $_POST['type'] );?></select>
				<select name="course_id" style="height:25px;width:180px;" ><?php Reset_Select_Key( $course_list , $_POST['course_id'] );?></select>
				<select name="option_name" style="height:25px;width:110px;" ><?php Reset_Select_Key( $gOption3 , $_POST['option_name'] );?></select>
				<select name="is_loan_only" style="height:25px;width:100px;" ><?php Reset_Select_Key( $gIsLoanOnly , $_POST['is_loan_only'] );?></select>
				<!--<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" />件/頁</span>-->
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='index.php';return true" />
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
	<div id="content-table">
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
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約番号</font></a></th>	
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">コース</font></a></th>							<!--新規売上/新規客数。店販追加予定-->
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">請求金額</font></a></th>							<!--レジへの遷移-->
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
							while ( $data = $dRtn3->fetch_assoc() ) {
								//役務消化除外
								if($data['r_times_flg'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card']) continue;

								// ローンステータスを契約tableから判定する
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

								// ローン取消・ローン非承認の場合、売掛金を0円で計算する
								if( $data['type']==9 || $data['type']==15 ) $data['balance']=0;

								echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
								if($data['type']<3 || $data['type']==51){
								echo 	'<td>'.$gResType3[$data['type']].'</td>';
								}else{
								echo 	'<td><font color="red">'.($course_type[$data['course_id']] ? $gResType6[$data['type']] : $gResType3[$data['type']]).'</font></td>';
								}
								echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
								echo 	'<td>'.$data['pay_date'].'</td>';
								echo 	'<td>'.$data['no'].'</td>';
								echo 	'<td><a  rel="facebox" href="../customer/mini.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
								echo 	'<td>'.$data['pid'].'</td>';

								// 1つのコース/複数コース表示出しわけ
								if(is_numeric($data['multiple_course_id'])){
									echo 	'<td><span class="c_couse_oen">'.$course_list[$data['multiple_course_id']].'</span></td>';
								} else {
									// 複数コースIDがあるときは分解した配列を作り、各コース名を表示する
									$multiple_course = explode(',', $data['multiple_course_id']);
									echo 	'<td><ul class="c_couse_list">';
									foreach ($multiple_course as $key => $value) {
										echo 	'<li>'.$course_list[$value].'</li>';
									}
									echo 	'</ul></td>';
								}

								//echo 	'<td>'.$course_list[$data['course_id']].'</td>';

		echo 	'<td class="priceFormat">'.( $data['type']==51 ? "-" :(($data['type']==4 || $data['type']==5 || $data['type']==9 || $data['type']==12) ? 0 : number_format($data['price']))).'</td>';//請求金額（商品金額）
		echo 	'<td>'.($data['type']==51 ? "物販" : $gOption2[$data['option_name']]).'</td>'; //ｵﾌﾟｼｮﾝ名
								echo 	'<td class="priceFormat">'.number_format($data['option_price'] + $data['option_transfer'] + $data['option_card']).'</td>'; 							//ｵﾌﾟｼｮﾝ金額
								echo 	'<td class="priceFormat">'.number_format($data['payment_cash'] + $data['option_price']).'</td>'; 	//現金入金
								echo 	'<td class="priceFormat">'.number_format($data['payment_card'] + $data['option_card']).'</td>'; 							//カード入金
								echo 	'<td class="priceFormat">'.number_format($data['payment_transfer']+ $data['option_transfer']).'</td>'; 						//銀行振込
								echo 	'<td class="priceFormat">'.number_format($data['payment_loan']).'</td>'; 							//ﾛｰﾝ
								echo 	'<td class="priceFormat">'.number_format($data['payment_coupon']).'</td>'; 							//CP
								echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>'; 								//売掛金
								echo '</tr>';

								// カウンセリング・1回コース当日・プラン変更時のみコース金額を計算する
								if($data['type']==1 || $data['type']==6 || $data['type']==20)$total_fixed_price += $data['fixed_price']; 				// コース金額,契約時のカウンセリング時だけ
								if($data['type']==1 || $data['type']==6 || $data['type']==20)$total_discount += $data['discount']; 						// 値引き合計,契約時のカウンセリング時だけ
								if($data['type']==1 || $data['type']==6 || $data['type']==20)$total_price += $data['fixed_price'] - $data['discount']; 	// 請求金額（商品金額）
								// 残金支払合計
								if($data['type']<>1 && $data['type']<>6 && $data['type']<>10){
									if( $data['payment_cash']>0)$total_payment += $data['payment_cash'] ;
									if( $data['payment_card']>0)$total_payment += $data['payment_card'] ;
									if( $data['payment_transfer']>0)$total_payment += $data['payment_transfer'] ;
									if( $loan_status==1 && $data['payment_loan']>0)$total_payment += $data['payment_loan'] ;
								}

								// オプション金額の計算
								$total_option_price += $data['option_price'] + $data['option_transfer'] + $data['option_card']; 			// オプション金額合計
								if($data['option_name']>=10 && $data['option_name']<=13 )$total_campaign += $data['option_price'] + $data['option_card']; // キャンペーン金額合計

								//$total_sales += $data['payment'] + $data['option_price']+ $data['payment_coupon']; 						// 入金金額（売上合計）
								$total_sales += $data['payment_cash'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon'] + $data['option_price'] + $data['option_transfer'] + $data['option_card']; // 入金金額（売上合計）

								//if($data['type']==1){
								if($data['type']==1 || $data['type']==20){
									if($pre_no == $data['no']) $total_balance -= $pre_balance; 	//最新の売掛だけを計上処理
									$total_balance += $data['balance']; // 売掛金合計
								}

								// if($data['type']==1){
								// 	if($course_type[$data['course_id']]) $cnt_monthly++; 		// 月額件数
								// 	else $cnt_pack++; // パック件数
								// }
								// 契約者数 カウンセリング OR 1回コース当日 コースIDがある人のみ集計する
								if(($data['type']==1 || $data['type']==20) && $data['multiple_course_id']){
									//if($course_type[$data['course_id']]) $cnt_monthly++; 		// 月額件数
									//else $cnt_pack++; // パック件数
									$cnt_pack++; // パック件数
								}

								$total_cash 	+= $data['payment_cash'] + $data['option_price']; 	// 現金売上合計
								$total_card 	+= $data['payment_card']  + $data['option_card']; 							// カード売上合計
								$total_transfer += $data['payment_transfer'] + $data['option_transfer']  ; 						// 銀行振込合計
								$total_loan 	+= $data['payment_loan'] ; 							// ローン売上合計
								$total_coupon 	+= $data['payment_coupon'] ; 						// クーポン売上合計

								//月額退会合計
								// if($data['type']==5 && $course_type[$data['course_id']] ) {
								// 	$total[20] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
								// }else{
									$total[$data['type']] += $data['payment_cash'] + $data['option_price']+ $data['option_transfer'] + $data['option_card'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon']; //区分別集計
								// }

								if($data['type']==4) $cnt4++; // クーリングオフ
								if($data['type']==5 && !$course_type[$data['course_id']] ) $cnt5++; //中途解約件数
								// if($data['type']==5 && $course_type[$data['course_id']] )  $cnt20++; //月額退会件数
								if($data['type']==6) $cnt6++; 	// プラン変更
								if($data['type']==7) $cnt7++;	// 売掛回収
								if($data['type']==12) $cnt12++; // 自動解約
		if($data['type']==51){/*$cnt51 += $data['product_count'];*/$cnt51_noprice += ($data['option_price']==0 && $data['option_card']==0 ? 1 : 0);$total_cnt51 += ($data['option_price'] + $data['option_card']);$cnt_product++;}//物販出庫数、プレゼント数、物販のみの売上、物販購入者数

								$pre_no = $data['no'];
								$pre_balance = $data['balance'];
								//最新売掛金を格納
								// if($data['type']==1 || $data['type']==20) $isexited_contract = true; // 売掛がある場合はtrue
								$isexited_contract = true; // 売掛がある場合はtrue 20160420 区分フィルタをかけると売掛金が0円になるためコメントアウトshimada

								// 最新売掛金を格納
								// $balance[$data['customer_id']] = $data['balance'];
								$balance[$data['pid']] = $data['balance']; // 最新の親契約ID(contract.pid)の売掛金を格納していく
								$i++;
							}
						?>
						<tr>
							<td class="table-header-repeat2 line-left">区分</td>
							<td class="table-header-repeat2 line-left">店舗</td>
							<td class="table-header-repeat2 line-left">日付</td>

							<td class="table-header-repeat2 line-left">会員番号</td>
							<td class="table-header-repeat2 line-left">顧客氏名</td>
							<td class="table-header-repeat2 line-left">契約番号</td>	
							<td class="table-header-repeat2 line-left">コース</td>							<!--新規売上/新規客数。店販追加予定-->
							<td class="table-header-repeat2 line-left">請求金額</td>							<!--レジへの遷移-->
							<td class="table-header-repeat2 line-left">ｵﾌﾟｼｮﾝ名</td>
							<td class="table-header-repeat2 line-left">ｵﾌﾟｼｮﾝ金額</td>

							<td class="table-header-repeat2 line-left" title="ｵﾌﾟｼｮﾝ金額込">現金入金</td>
							<td class="table-header-repeat2 line-left" title="ｵﾌﾟｼｮﾝ金額込">カード入金</td>
							<td class="table-header-repeat2 line-left" title="ｵﾌﾟｼｮﾝ金額込">銀行振込</td>
							<td class="table-header-repeat2 line-left" title="既払金込">ﾛｰﾝ</td>
							<td class="table-header-repeat2 line-left" title="クーポン入金">CP</td>
							<td class="table-header-repeat2 line-left">売掛金</td>
						</tr>
							<?php
								$total_balance = array_sum($balance);
								$total_balance = $isexited_contract ? $total_balance : 0; 			// 契約データがなければ0
								$total_without_balance = $total_sales; 								// 売掛含まない総合計
								echo '<tr class="'. ( $i%2==0 ? 'alternate-row ' : '' ) .'">';
								echo 	'<td>合計</td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								echo 	'<td></td>';
								//echo 	'<td class="priceFormat">'.number_format($total_price).'</td>'; 		//請求金額,プラン変更に対応していない

								echo 	'<td></td>';
								echo 	'<td class="priceFormat">'.number_format($total_option_price).'</td>'; 	//ｵﾌﾟｼｮﾝ金額
								echo 	'<td class="priceFormat">'.number_format($total_cash).'</td>';			//現金入金
								echo 	'<td class="priceFormat">'.number_format($total_card).'</td>';			//カード入金
								echo 	'<td class="priceFormat">'.number_format($total_transfer).'</td>'; 		//銀行振込
								echo 	'<td class="priceFormat">'.number_format($total_loan).'</td>'; 			//ローン
								echo 	'<td class="priceFormat">'.number_format($total_coupon).'</td>';		//CP
								echo 	'<td class="priceFormat">'.number_format($total_balance).'</td>';		//売掛金
								echo '</tr>';

								echo '<tr><td colspan="15"></td><td>内訳</td>';
								//echo '<tr><td colspan="15" class="priceFormat">月額:</td><td class="priceFormat">'.number_format($cnt_monthly).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">契約者数:</td><td class="priceFormat">'.number_format($cnt_pack).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">未契約者数:</td><td class="priceFormat">'.number_format($dGet_Cnt4).' 名</td></tr>';

								//来店件数=未契約数+契約数（月額件数+パック件数）
								$cnt_came = $dGet_Cnt4 + $cnt_monthly + $cnt_pack ;
								//カウンセリング予約件数=来店件数+来店なし件数
								$cnt_total = $cnt_came + $dGet_Cnt5 ;
								//来店率=来店件数/カウンセリング予約件数*100%
								if($cnt_total) $percent_came = round($cnt_came/$cnt_total*100)."%";
								//成約率=成約件数/来店件数*100%
								if($cnt_came) $percent_contract = round(($cnt_monthly + $cnt_pack)/$cnt_came*100)."%";

								echo '<tr><td colspan="15" class="priceFormat">来店率:</td><td class="priceFormat">'.$percent_came.'</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">成約率:</td><td class="priceFormat">'.$percent_contract.'</td></tr>';

							if(isset($total['4'])){
								echo '<tr><td colspan="15" class="priceFormat">クーリングオフ:</td><td class="priceFormat">'.number_format($cnt4).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">クーリングオフ返金:</td><td class="priceFormat">'.number_format($total['4']).' 円</td></tr>';
							}
							if(isset($total['5'])){
								echo '<tr><td colspan="15" class="priceFormat">中途解約:</td><td class="priceFormat">'.number_format($cnt5).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">中途解約返金:</td><td class="priceFormat">'.number_format($total['5']).' 円</td></tr>';
							}
							// if(isset($total['20'])){
							// 	echo '<tr><td colspan="15" class="priceFormat">月額退会:</td><td class="priceFormat">'.number_format($cnt20).' 名</td></tr>';
							// 	echo '<tr><td colspan="15" class="priceFormat">月額退会返金:</td><td class="priceFormat">'.number_format($total['20']).' 円</td></tr>';
							// }
							if(isset($total['6'])){
								echo '<tr><td colspan="15" class="priceFormat">プラン変更:</td><td class="priceFormat">'.number_format($cnt6).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">プラン変更差額:</td><td class="priceFormat">'.number_format($total['6']).' 円</td></tr>';
							}
							/*if(isset($total['7'])){
								echo '<tr><td colspan="15" class="priceFormat">売掛回収:</td><td class="priceFormat">'.number_format($cnt7).' 名</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">売掛回収金額:</td><td class="priceFormat">'.number_format($total['7']).' 円</td></tr>';
							}*/
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
								echo '<tr><td colspan="15" class="priceFormat">客単価((売掛含む総合計 - 物販売上)/来店数):</td><td class="priceFormat">'.number_format(($total_balance + $total_without_balance - $total_cnt51)/($cnt_monthly+$cnt_pack+$dGet_Cnt4)).' 円</td></tr>';
								//echo '<tr><td colspan="15" class="priceFormat">キャンペーン売上:</td><td class="priceFormat">'.number_format($total_campaign).' 円</td></tr>';
								echo '<tr class="hr"><td colspan="15" class="priceFormat">物販利用者:</td><td class="priceFormat">'.number_format($cnt_product).' 人</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">物販売上:</td><td class="priceFormat">'.number_format($total_cnt51).' 円</td></tr>';
								echo '<tr><td colspan="15" class="priceFormat">物販客単価（物販売上/物販利用者）:</td><td class="priceFormat">'.number_format($total_cnt51/$cnt_product).' 円</td></tr>';
		// echo '<tr><td colspan="15" class="priceFormat">物販出庫数:</td><td class="priceFormat">'.number_format($cnt51).' 個</td></tr>';
		echo '<tr><td colspan="15" class="priceFormat">（物販無料プレゼント人数）:</td><td class="priceFormat">('.number_format($cnt51_noprice).' 人)</td></tr>';
						}
					?>
				</table>
				<!--  end product-table................................... -->
				<!-- ※ 契約時（区分：カウンセリング）合計に計上する項目：コース金額、値引、請求金額（税込）、売掛金(表示期間内の最新売掛だけを計上)、月額件数、パック件数 -->
				※ 契約時（区分：カウンセリング、1回コース当日）合計に計上する項目：コース金額、値引、請求金額（税込）、売掛金(表示期間内の最新売掛だけを計上)、パック件数
				</form>

				<!-- 売掛含む総合計詳細ポップアップ -->
				<div id="top_right_amount">
			    <div id="top_right_amount_inner">
			        <?php
			       /* echo '月額:'.number_format($cnt_monthly).' 名';
			        echo '/パック:'.number_format($cnt_pack).' 名';
			        if(isset($total['4'])){
			        	echo '/クーリングオフ:'.number_format($cnt4).' 名';
			        	echo '/クーリングオフ返金:'.number_format($total['4']).' 名';
			        }
			        if(isset($total['5'])){
			        	echo '/中途解約:'.number_format($cnt5).' 名';
			        	echo '/中途解約返金:'.number_format($total['5']).' 名';
			        }
			        if(isset($total['6'])){
			        	echo '/プラン変更:'.number_format($cnt6).' 名';
			        	echo '/プラン変更返金:'.number_format($total['6']).' 名';
			        }
			        if(isset($total['7'])){
			        	echo '/売掛回収:'.number_format($cnt7).' 名';
			        	echo '/売掛回収金額:'.number_format($total['7']).' 名';
			        }
			        echo '<br>現金売上:'.number_format($total_cash).' 円';
					echo '/カード売上:'.number_format($total_card).' 円';
					echo '/銀行振込:'.number_format($total_transfer).' 円';
					echo '/ローン売上:'.number_format($total_loan).' 円';
					echo '/売掛金:'.number_format($total_balance).' 円';
					echo '<br>売掛含む総合計:'.number_format($total_balance + $total_without_balance).' 円';*/

					$param .= '?cnt_monthly='.$cnt_monthly.'&cnt_pack='.$cnt_pack;
					$param .= '&cnt4='.$cnt4.'&tatal4='.$total['4'].'&cnt5='.$cnt5.'&tatal5='.$total['5'].'&cnt6='.$cnt6.'&tatal6='.$$total['6'].'&cnt7='.$cnt7.'&tatal7='.$total['7'];
					$param .= '&total_cash='.$total_cash.'&total_card='.$total_card.'&total_transfer='.$total_transfer.'&total_loan='.$total_loan.'&total_balance='.$total_balance;
					$param .= '&total_incloude='.($total_balance + $total_without_balance).'&total_uncloude='.$total_without_balance;
					echo '<a class="side" rel="facebox" href="report.php'.$param.'">売掛含まない総合計: '.number_format($total_without_balance).' 円</a>';					
					?>
				</div>
				</div>
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
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>