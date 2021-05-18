<?php include_once("../library/account/loan.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			ローン一覧
			<span style="margin-left:20px;">
				<a href="./loan.php?contract_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="contract_date" type="text" id="day" value="<?php echo $_POST['contract_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="contract_date2" type="text" id="day2" value="<?php echo $_POST['contract_date2'];?>" readonly  />
				<a href="./loan.php?contract_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				<select name="status" style="height:25px;"><option value="">-</option><?php Reset_Select_Key( $gLoanStatus , $_POST['status'] );?></select>

				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
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
					<?php echo "<font color='red' size='-1'>".$gMsg.$_REQUEST['gMsg'] ."</font>";?>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">売掛金</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ローン</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">原本郵送</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">承認状態</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">主担当</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""></a></th>

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//月額制除外
		//if( $course_type[$data['course_id']] ) continue;
		if($data['times']) $price_once = round($data['price'] / $data['times'] , 0); // 金額/回
		$price_used =  $price_once * $data['r_times'] ; // 消化金額
		$price_remain = $data['price'] - $price_used;  // 役務残
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td><a  rel="facebox" href="../customer/mini.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		
		//echo 	'<td>'.$course_list[$data['course_id']].'</td>';
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

		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['payment_loan']).'</td>';
		echo 	'<td>'.$gContractSend[$data['contract_send']].'</td>';
		echo 	'<td>'.$gLoanStatus[$data['loan_status']].'</td>';
		echo 	'<td>'.$staff_list[$data['staff_id']].'</td>';
		echo 	'<td>';
		// ローン非承認・ローン取消はリンクを設置しない 20160805 shimada
		if($authority_level<=6 AND $data['loan_status']<>2 AND $data['loan_status']<>4) echo '<a rel="facebox" href="../service/confirm_loan.php?mode=from_loan_list&customer_id='.$data['customer_id'].'&pid='.$data['id'].'&shop_id='.$_POST['shop_id'].'&start='.$_POST['start'].'&contract_date='.$_POST['contract_date'].'&contract_date2='.$_POST['contract_date2'].'&status='.$_POST['status'].'&line_max='.$_POST['line_max'].'" title="ローン承認処理" class="icon-1 info-tooltip"></a>';
		echo 	'</td>';
		echo '</tr>';
		$i++;
	}
		/*echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>合計</td>';
		echo 	'<td></td>';
		echo 	'<td</td>';
		echo 	'<td</td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo '</tr>';*/
}
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
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
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