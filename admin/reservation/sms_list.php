<?php include_once("../library/reservation/sms_list.php");?>
<?php include_once("../include/header_menu.html");?>

<script type="text/javascript">
function csv_export () {
      document.search.action = "sms_csv.php";
	  document.search.submit();
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			SMS配信対象（前日予約確認）
			<span style="margin-left:20px;">
				<!-- <select name="search_shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['search_shop_id'] );?></select> -->
				<select id="shop_id" name="search_shop_id" style="height:25px;" ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['search_shop_id'], $gArea_Group, "area_group"); ?></select>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			<span>
			<span style="font-size:12px;">
				※予約日が明後日、登録日が「本日」OR「前日」まで電話不通のお客様
			</span>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">同伴者</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">同伴者TEL</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">予約日時</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">登録日時</a></th>

				</tr>
<?php
if ($dRtn3) {
	$i=1;
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td><a href="edit.php?reservation_id='.$data['rid'].'">'.$data['name_kana'].'</a></td>';
		echo 	'<td>'.$data['tel'].'</td>';
		echo 	'<td>'.$data['pair_name_kana'].'</td>';
		echo 	'<td>'.$data['pair_tel'].'</td>';
		echo 	'<td>'.$data['hope_date'].' '.$gTime2[$data['hope_time']].'</td>';
		echo 	'<td>'.$data['reg_date'].'</td>';
		echo '</tr>';
		$i++;

	}
}

?>

				</table>
				<!--  end product-table................................... -->
				</form>
			</div>
			<!--  end content-table  -->
			<div style="text-align:right;margin-right:10px;">ヒット件数：<?php echo $i ;?>件</div>
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