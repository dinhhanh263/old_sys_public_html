<?php include_once("../library/account/loan.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">
function display () {
    document.search.action = "loan.php";
    document.search.submit();
	return fales;
}
</script>

<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "csv_loan.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
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
			ローン一覧
			<span style="margin-left:20px;">
				<a href="./loan.php?application_date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="application_date" type="text" id="day" value="<?php echo $_POST['application_date'];?>" readonly />~
				<input style="width:70px;height:21px;" name="application_date2" type="text" id="day2" value="<?php echo $_POST['application_date2'];?>" readonly />
				<a href="./loan.php?application_date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>


        		<select id="shop_id" name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<select name="status" style="height:25px;"><option value="">-</option><?php Reset_Select_Key( $gLoanStatus , $_POST['status'] );?></select>

				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type='button' value=' 表示 ' onclick='display();' style="height:25px;" />
			</span>
			<?php if($authority_level<=6){?>
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
					<?php echo "<font color='red' size='-1'>".$gMsg.$_REQUEST['gMsg'] ."</font>";?>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">区分</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ローン申込日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ローン会社</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">顧客名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">購入コース</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">請求金額</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">売掛金</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ローン</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">原本郵送</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">承認状態</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">主担当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2"></font></a></th>

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$gContractStatus[$data['status']].'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
		echo 	'<td>'.($data['loan_application_date']=='0000-00-00' ? '' : $data['loan_application_date']).'</td>';
		echo 	'<td>'.($data['loan_company_id'] ? $loan_company_list[$data['loan_company_id']] : '').'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.($data['name'] ? $data['name'] : $data['name_kana']).'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['balance']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['payment_loan']).'</td>';
		echo 	'<td>'.$gContractSend[$data['contract_send']].'</td>';
		echo 	'<td>'.$gLoanStatus[$data['loan_status']].'</td>';
		echo 	'<td>'.$staff_list[$data['staff_id']].'</td>';
		echo 	'<td>';
		if($authority_level<=6) echo '<a rel="facebox" href="../service/confirm_loan.php?contract_id='.$data['id'].'" title="ローン承認処理" class="icon-1 info-tooltip"></a>';
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