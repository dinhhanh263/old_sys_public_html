<?php include_once("../library/sales/staff.php");?>
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
// カウンセラー売上一覧出力
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "csv_staff.php";
	  document.search.submit();
	  return fales;
  	};
}
// カウンセラー別未契約人数一覧出力
function csv_export_no_contract () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "csv_staff_no_contract.php";
	  document.search.submit();
	  return fales;
  	};
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			カウンセラー売上
			<span style="margin-left:20px;">
				<select name="if_sort" style="height:25px;"><option>ソート設定なし</option>
					<option value="1" <?php if($_POST['if_sort']==1||($_POST['if_sort']==0 && $_POST['status']<>2 && $_POST['status']<>3 && $_POST['status']<>6)) echo "selected";?>>契約日順</option>
					<option value="2" <?php if($_POST['if_sort']==2||($_POST['if_sort']==0 && ($_POST['status']==2||$_POST['status']==3||$_POST['status']==6))) echo "selected";?>>解約日順</option>
					<option value="3" <?php if($_POST['if_sort']==3||($_POST['if_balance']==1)) echo "selected";?>>支払完了日順</option>
				</select>
				<a href="./staff.php?contract_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&if_sort=<?php echo $_POST['if_sort'];?>&if_balance=<?php echo $_POST['if_balance'];?>&status=<?php echo -1 < $_POST['status']?$_POST['status']:-1 ;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="contract_date" type="text" id="day" value="<?php echo $_POST['contract_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="contract_date2" type="text" id="day2" value="<?php echo $_POST['contract_date2'];?>" readonly  />
				<a href="./staff.php?contract_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&if_sort=<?php echo $_POST['if_sort'];?>&if_balance=<?php echo $_POST['if_balance'];?>&status=<?php echo -1 < $_POST['status']?$_POST['status']:-1 ;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>

				<select name="staff_id"  style="height:25px;"><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$_POST['contract_date']) , $_POST['staff_id'] ? $_POST['staff_id'] : $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select>
				<select name="if_balance" style="height:25px;">
					<option>残金状況</option>
					<option value="1" <?php if($_POST['if_balance']==1) echo "selected";?>>支払完了</option>
					<option value="2" <?php if($_POST['if_balance']==2) echo "selected";?>>残金あり</option>
				</select>
				<select name="status" style="height:25px;" ><?php Reset_Select_Key( $gContractStatus5 , -1<$_POST['status'] ? $_POST['status'] : -1);?></select>
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='staff.php';return true" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
			</span>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV(未契約者)　' onclick='csv_export_no_contract();' style="height:25px;" />
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
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">解約日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">実入金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">売掛金</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払完了日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ｶｳﾝｾﾗｰ</a></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$i_plan = 0; // プラン変更
	$i_end = 0; // 契約終了、返金保証回数終了
	$i_cancel = 0; // 解約
	$i_wait = 0; // 契約待ち
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		if( ($_POST['if_balance']==1) && $data['payment_loan'] && $data['loan_status']<>1) continue;

		if($data['status']==5) $price=$data['price']-$data['balance']-$data['payment_loan'];																				//ローン取消の場合、ローンを０に
		else $price = $data['price']-$data['balance'];
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		if($data['status']==0){
		echo 	'<td>'.$gContractStatus[$data['status']].'</td>';
		}else{
		//echo 	'<td><font color="red">'.($course_type[$data['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]).'</font></td>';
			if(!$course_type[$data['course_id']]){
				echo 	'<td><font color="red">'.($data['status']==4 && $data['conversion_flg'] ? "プラン組替" : $gContractStatus7[$data['status']]).'</font></td>';// 契約中以外、赤文字表記(パック) 20160902 shimada
			} else {
				echo 	'<td><font color="red">'.$gContractStatus6[$data['status']].'</font></td>';// 契約中以外、赤文字表記(月額) 20160902 shimada
			}
		}	
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.($data['cancel_date']<>"0000-00-00" ? $data['cancel_date'] : "").'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.($data['name'] ? $data['name'] : $data['name_kana']).'</td>';
		// プラン変更済みの場合、旧コース⇒新コースの表記にする
		if($data['old_course_id']<>0){
			echo 	'<td>【旧】'.$course_list[$data['old_course_id']].'<br>【新】'.$course_list[$data['course_id']].'</td>';
		} else {
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		}
		echo 	'<td class="priceFormat">'.number_format($data['fixed_price']-$data['discount']).'</td>'; 									// 契約金額
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>'; 									// 請求金額
		if($data['status']==2 || $data['status']==3){
		echo 	'<td class="priceFormat">0</td>'; 					//実入金額(クーリングオフ、中途解約)
		}else{	
		echo 	'<td class="priceFormat">'.number_format($price).'</td>'; 					//実入金額,複数支払のため、
		//echo 	'<td class="priceFormat">'.number_format($data['payment']).'</td>'; 					//実入金額
		}
		// ローン支払が0円ではない && (ローン状況が 3.承認中 OR 5.ローン不備 OR 承認中(OK) の場合、赤文字で表示する) 2017/08/07 modify by shimada
		if($data['payment_loan']<>0 && ($data['loan_status']==3 || $data['loan_status']==5 || $data['loan_status']==6)){
			echo 	'<td class="priceFormat"><font color=red>'.number_format($data['balance']).'</font></td>';									//売掛金
		}else{
			echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>';									//売掛金
		}
		

		echo 	'<td>'.($data['pay_complete_date']<>"0000-00-00" ? $data['pay_complete_date'] : "").'</td>';
		echo 	'<td>'.$staff_list[$data['staff_id']].'</td>';
		echo '</tr>';

		$total_contract_price += $data['fixed_price']-$data['discount'];
		$total_price += $data['price'];
		if($data['status']<>2 && $data['status']<>3) $total_payment += $data['price']-$data['balance'];
		//if($data['status']<>2 && $data['status']<>3) $total_payment += $data['payment'];
		$total_balance += $data['balance'];
		$cnt[$data['course_id']] +=1;
		$i++;
		if($data['status']==1||$data['status']==8)$i_end++; // 契約/返金保証終了カウント
		elseif($data['status']==4)$i_plan++; // 解約カウント
		elseif($data['status']==2||$data['status']==3||$data['status']==6)$i_cancel++; // 解約カウント
		elseif($data['status']==7)$i_wait++; // 契約待ちカウント
	}
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td colspan="7">合計</td>';
		echo 	'<td class="priceFormat">'.number_format($total_contract_price).'</td>';		// 契約金額
		echo 	'<td class="priceFormat">'.number_format($total_price).'</td>';		// 請求金額
		//echo 	'<td class="priceFormat"></td>';		// 請求金額
		echo 	'<td class="priceFormat">'.number_format($total_payment).'</td>';	//実入金額
		echo 	'<td class="priceFormat">'.number_format($total_balance).'</td>';	//売掛金
		echo 	'<td colspan="2"></td>';
		echo '</tr>';
		echo '<tr><td colspan="7" class="priceFormat">契約数</td><td colspan="6">'.$i.'名(終了'.$i_end.'名/解約'.$i_cancel.'名/待ち'.$i_wait.'名/プラン変更'.$i_plan.'名)</td><tr>';
		echo '<tr><td colspan="7" class="priceFormat">未契約数</td><td colspan="6">'.$dGet_Cnt.'名</td><tr>';
		echo '<tr><td colspan="7" class="priceFormat">トータル数</td><td colspan="6">'.($i + $dGet_Cnt).'名</td><tr>';
		krsort($cnt);
	foreach($cnt as $key => $val){
		echo '<tr>';
		echo 	'<td colspan="7" class="priceFormat">'.$course_list[$key].'</td>';
		echo 	'<td colspan="6">'.$val.'名</td>';
		echo '</tr>';
	}

}
?>
				
				</table>
				<!--  end product-table................................... --> 
				※ 契約状況：デフォルト（契約日順）、支払完了（支払完了日順、カウンセラー売上計算基準）、残金あり（契約日順）<br>
				※ ソート設定：ソート設定した場合、ソート設定が優先されます。ソート設定していない場合、クーリングオフ・中途解約(月額退会)・自動解約は解約日順、その他は契約日順で表示されます。<br>
				※ 残金状況：「支払完了」を設定すると、支払完了日順に表示されます。<br>
				※ 契約数：終了(契約終了、返金保証回数終了)、解約(クーリングオフ/中途解約/自動解約)、待ち(契約待ち)、プラン変更(プラン変更)<br>
				※ 契約区分検索：設定した場合、現在の契約データを参照し、検索できます。<br>
				</form>
			</div>
			<!--  end content-table  -->

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