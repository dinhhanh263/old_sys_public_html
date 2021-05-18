<?php include_once("../library/account/remain.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			消化管理
			<span style="margin-left:20px;">
				<a href="./remain.php?latest_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="latest_date" type="text" id="day" value="<?php echo $_POST['latest_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="latest_date2" type="text" id="day2" value="<?php echo $_POST['latest_date2'];?>" readonly  />
				<a href="./remain.php?latest_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!-- <select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select> -->
				<select id="shop_id" name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<!--<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>-->
				<!--<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>-->
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
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約コース</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">役務残</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化回数</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">最終消化日</a></th>

					<th class="table-header-repeat line-left minwidth-1"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//月額制除外
		if( $course_type[$data['course_id']] ) continue;
		//if($data['times']) $price_once = round($data['price'] / $data['times'] , 0); // 金額/回
		if($course_times[$data['course_id']]) $price_once = round($data['price'] / $course_times[$data['course_id']] , 0); // 金額/回
		else $price_once = 0;


		$price_used =  $price_once * $data['r_times'] ; // 消化金額
		$price_remain = $data['price'] - $price_used;  // 役務残
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		//echo 	'<td><a href="../customer/edit.php?id='.$data['customer_id'].'">'.$data['name'].'</a></td>';
		echo 	'<td>'.($data['name'] ? $data['name'] :$data['name_kana']).'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_used).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_remain).'</td>';
		echo 	'<td class="priceFormat">'.$data['r_times'].'</td>';
		echo 	'<td>'.($data['latest_date'] == "0000-00-00" ? "" : $data['latest_date']).'</td>';
		echo 	'<td class="options-width">';
		//echo 		'<a href="edit.php?id='.$data['id'].'" title="Edit" class="icon-1 info-tooltip"></a>';
		echo 	'</td>';
		echo '</tr>';

		if($data['r_times']){
			$cnt_all++; //月額+パック
			if($course_type[$data['course_id']]) $cnt_monthly++; // 月額件数
			else $cnt_pack++; // パック件数
		}

		$total_price += $data['price'];
		$total_price_used += $price_used;
		$total_price_remain += $price_remain;

		$i++;
	}
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td colspan="4">合計</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price).'</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_used).'</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_remain).'</td>';
		echo 	'<td class="priceFormat">'.number_format($cnt_all).'</td>';
		echo 	'<td colspan="2"></td>';
		echo '</tr>';
}
?>

				</table>
				<!--  end product-table................................... -->
				※　契約DBより取得、最終消化データ表示。
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