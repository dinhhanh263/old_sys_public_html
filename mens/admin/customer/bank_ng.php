<?php include_once("../library/customer/bank_ng.php");?>
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
			銀行月額引落NG一覧
			<span style="margin-left:20px;">
				<a href="./bank_ng.php?contract_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="contract_date" type="text" id="day" value="<?php echo $_POST['contract_date'];?>" />~<input style="width:70px;height:21px;" name="contract_date2" type="text" id="day2" value="<?php echo $_POST['contract_date2'];?>" />
				<a href="./bank_ng.php?contract_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="search_shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['search_shop_id']);?></select>
				<select name="bank_ng_flg" style="height:25px;" ><?php Reset_Select_Key( $gBankNG , $_POST['bank_ng_flg']);?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" /> <!--CSV export-->
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
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">NGパターン</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">入金金額（税込）</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">売掛金</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">登録日時</a></th>
					<th class="table-header-repeat line-left"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="frm'.$i.'">';
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$gBankNG[$data['bank_ng_flg']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td title="'.$data['name'].'"><a  rel="facebox" href="mini.php?id='.$data['customer_id'].'">'.($data['name_kana'] ? $data['name_kana'] : $data['name']).'</a></td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['payment']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.$data['reg_date'].'</td>';
		echo 	'<td><a href="javascript:document.forms[\'frm'.$i.'\'].submit();" onclick="return confirm(\''.$data['no'].'を変更しますか？\')" title="OK" >OK</a></td>';
		echo '</tr>';
		echo '<input name="action" type="hidden" value="ok">';
		echo '<input name="id" type="hidden" value="'.$data['customer_id'].'">';
		echo '<input name="search_shop_id" type="hidden" value="'.$_POST['search_shop_id'].'">';
		echo '<input name="bank_ng_flg" type="hidden" value="'.$_POST['bank_ng_flg'].'">';
		echo '<input name="contract_date" type="hidden" value="'.$_POST['contract_date'].'">';
		echo '<input name="contract_date2" type="hidden" value="'.$_POST['contract_date2'].'">';
		echo '</form>';
		$i++;
	}
}
?>
				</table>
				<!--  end product-table................................... --> 

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