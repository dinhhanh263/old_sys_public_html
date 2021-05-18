<?php include_once("../library/account/loan_pass_days.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_loan.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
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
			ローン経過日数
			
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約状況</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ローン状況</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ローン会社</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ローン申込日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">ローン申込金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">経過日数</a></th>
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href=""></a></th> -->
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.($data['type']==6 && $data['conversion_flg'] ? "プラン組替" : $gResType3[$data['type']]).'</td>';
		echo 	'<td>'.$data['会員番号'].'</td>';
		echo 	'<td>'.($data['名前'] ? $data['名前'] : $data['名前カナ']).'</td>';
		echo 	'<td>'.$gContractStatus[$data['契約状況']].'</td>';
		echo 	'<td>'.$gLoanStatus[$data['ローン状況']].'</td>';
		echo 	'<td>'.$data['ローン会社'].'</td>';
		echo 	'<td>'.$data['契約日'].'</td>';
		echo 	'<td>'.$data['ローン申込日'].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['ローン申込金額']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['経過日数']).'</td>';
		// echo 	'<td>';
		// if($authority_level<=6) echo '<a rel="facebox" href="../service/confirm_loan.php?mode=from_loan_list&contract_id='.$data['tid'].'" title="ローン承認処理" class="icon-1 info-tooltip"></a>';
		// echo 	'</td>';
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