<?php include_once("../library/service/cancel.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			契約変更一覧
			<span style="margin-left:20px;">
				<a href="./cancel.php?cancel_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="cancel_date" type="text" id="day" value="<?php echo $_POST['cancel_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="cancel_date2" type="text" id="day2" value="<?php echo $_POST['cancel_date2'];?>" readonly  />
				<a href="./cancel.php?cancel_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				<select name="status" style="height:25px;" ><option value="">全区分</option><?php Reset_Select_Key( $gContractStatus4 , $_POST['status'] );?></select>
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
				<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約返金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">コース組換</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">カウンセリング担当</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ミドル担当</a></th>
				</tr>
<?php
if ( $dRtn3) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		if($data['new_contract_id'])$new_contract = Get_Table_Row("contract"," WHERE id = '".addslashes($data['new_contract_id'])."'");
		else $new_contract="";
		//if($data['status']==5) $payment = $data['payment']-$data['payment_loan'];
		if($data['status']==5) $payment = 0;
		elseif($data['status']<>4) $payment= $data['payment'] ;
		else $payment="";
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>'; 
		echo 	'<td>'.($course_type[$data['course_id']] ? $gContractStatus3[$data['status']] : $gContractStatus[$data['status']]).'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format(abs($payment)).'</td>';
		echo 	'<td>'.$course_list[$data['new_course_id']].'</td>';
		echo 	'<td>'.$staff_list[$data['staff_id']].'</td>';
		echo 	'<td>'.($new_contract['staff_id'] ? $staff_list[$new_contract['staff_id']] : "").'</td>';
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