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
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_staff.php";
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
			カウンセラー売上
			<span style="margin-left:20px;">
				<a href="./staff.php?contract_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&if_balance=<?php echo $_POST['if_balance'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="contract_date" type="text" id="day" value="<?php echo $_POST['contract_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="contract_date2" type="text" id="day2" value="<?php echo $_POST['contract_date2'];?>" readonly  />
				<a href="./staff.php?contract_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&if_balance=<?php echo $_POST['if_balance'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>

				<select name="staff_id"  style="height:25px;"><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$_POST['contract_date']) , $_POST['staff_id'] ? $_POST['staff_id'] : $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select>
				<select name="if_balance" style="height:25px;"><option>残金状況</option><option value="1" <?php if($_POST['if_balance']==1) echo "selected";?>>支払完了</option><option value="2" <?php if($_POST['if_balance']==2) echo "selected";?>>残金あり</option></select>
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='staff.php';return true" />
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
				<tr>
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th> -->
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">実入金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">売掛金</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払完了日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">カウンセラー</a></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		if( ($_POST['if_balance']==1) && $data['payment_loan'] && $data['loan_status']<>1) continue;

		if($data['status']==5) $price=$data['price']-$data['balance']-$data['payment_loan'];																				//ローン取消の場合、ローンを０に
		else $price = $data['price']-$data['balance'];
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		
		// 複数コースの契約状況があるためコメントアウト 20160512shimada
		// if(!$data['status']){
		// echo 	'<td>'.$gContractStatus[$data['status']].'</td>';
		// }else{
		// echo 	'<td><font color="red">'.($course_type[$data['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]).'</font></td>';
		// }	
		
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td><a rel="facebox" href="../customer/mini.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		
		// echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		// 1つのコース/複数コース表示出しわけ
		if(is_numeric($data['multiple_course_id'])){
			echo 	'<td><span class="c_couse_oen">'.$course_list[$data['multiple_course_id']].'</span></td>';
			$cnt[$data['multiple_course_id']] +=1;
			//$i++;
		} else {
			// 複数コースIDがあるときは分解した配列を作り、各コース名を表示する
			$multiple_course = explode(',', $data['multiple_course_id']);
			echo 	'<td><ul class="c_couse_list">';
			foreach ($multiple_course as $key => $value) {
				echo 	'<li>'.$course_list[$value].'</li>';
				$cnt[$value] +=1;
				//$i++;
			}
			echo 	'</ul></td>';
		}
		// 契約者数を人ごとにカウントする
		if($data['multiple_course_id'])$i++;

		echo 	'<td class="priceFormat">'.number_format($data['fixed_price']-$data['discount']).'</td>'; 									// 契約金額
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>'; 									// 請求金額
		if($data['status']==2 || $data['status']==3){
		echo 	'<td class="priceFormat">0</td>'; 					//実入金額(クーリングオフ、中途解約)
		}else{	
		echo 	'<td class="priceFormat">'.number_format($price).'</td>'; 					//実入金額,複数支払のため、
		//echo 	'<td class="priceFormat">'.number_format($data['payment']).'</td>'; 					//実入金額
		}
		if($data['payment_loan']<>0 && $data['loan_status']==3 ){
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
	}
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td colspan="6">合計</td>';
		echo 	'<td class="priceFormat">'.number_format($total_contract_price).'</td>';		// 契約金額
		echo 	'<td class="priceFormat">'.number_format($total_price).'</td>';		// 請求金額
		//echo 	'<td class="priceFormat"></td>';		// 請求金額
		echo 	'<td class="priceFormat">'.number_format($total_payment).'</td>';	//実入金額
		echo 	'<td class="priceFormat">'.number_format($total_balance).'</td>';	//売掛金
		echo 	'<td colspan="2"></td>';
		echo '</tr>';
		echo '<tr><td colspan="10" class="priceFormat">契約者数</td><td colspan="2">'.$i.'名</td><tr>';
		echo '<tr><td colspan="10" class="priceFormat">未契約者数</td><td colspan="2">'.$dGet_Cnt.'名</td><tr>';
		echo '<tr><td colspan="10" class="priceFormat">トータル数</td><td colspan="2">'.($i + $dGet_Cnt).'名</td><tr>';
		krsort($cnt);
	// コース別集計
	foreach($cnt as $key => $val){
		echo '<tr>';
		echo 	'<td colspan="10" class="priceFormat">'.$course_list[$key].'</td>';
		echo 	'<td colspan="2">'.$val.'名</td>';
		echo '</tr>';
	}

}
?>
				
				</table>
				<!--  end product-table................................... --> 
				※ 契約状況：デフォルト（契約日順）、支払完了（支払完了日順、カンセラー売上計算基準）、残金あり（契約日順）
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