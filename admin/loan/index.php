<?php include_once("../library/loan/index.php");?>
<?php if($authority_level>0) header("Location: ./");?>
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
    document.search.action = "index.php";
    document.search.submit();
	return false;
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>
			ローン申込一覧
			<span style="margin-left:20px;">
				<a href="./index.php?application_date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="application_date" type="text" id="day" value="<?php echo $_POST['application_date'];?>" readonly />~
				<input style="width:70px;height:21px;" name="application_date2" type="text" id="day2" value="<?php echo $_POST['application_date2'];?>" readonly />
				<a href="./index.php?application_date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type='button' value=' 表示 ' onclick='display();' style="height:25px;" />
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
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ID</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ステータス</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">受付番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申込日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">生年月日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">電話番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">確認状況</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">経過日数</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">当月支払回数</font></a></th>

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	$first_payment_month ='';
	$ym2 = date('Y-m');
	$date1 ='';
	$date2 ='';
	$month1 ='';
	$month2 ='';
	$month_diff ='';

	while ( $data = $dRtn3->fetch_assoc() ) {
		$first_payment_month = $data['first_payment_month']<10 ?
							   $data['first_payment_year'].'-0'.$data['first_payment_month'] :
							   $data['first_payment_year'].'-'.$data['first_payment_month'];
		$date1=strtotime($ym2.'-01');
		$date2=strtotime($first_payment_month.'-01');
		$month1=date("Y",$date1)*12+date("m",$date1);
		$month2=date("Y",$date2)*12+date("m",$date2);
		$month_diff = $month1 - $month2;

		echo '<tr'. ( $data['payment_loan']<=0 && !$data['amount'] ? ' style="background-color: yellow;"' : ($i%2==0 ? ' class="alternate-row"' : '') ) .'>';
		echo 	'<td>'.$data['id'].'</td>';
		echo 	'<td>'.$gLoanContractStatus[$data['contract_status']].'</td>';
		echo 	'<td>'.$data['recept_no'].'</td>';
		echo 	'<td>'.$data['loan_contract_no'].'</td>';
		// if($data['shop_id']>1000){
		// echo 	'<td>'.$data['no'].'</td>';
		// }else{
		echo 	'<td>'.$data['no'].'</td>';
		// }
		echo 	'<td>'.$data['application_date'].'</td>';
		echo 	'<td>'.($data['birthday']<>'0000-00-00' ? $data['birthday'] : $data['cbirthday']).'</td>';
		echo 	'<td>'.($data['tel'] ? $data['tel'] : $data['ctel']).'</td>';
		echo 	'<td>'.$gVerifyStatus[$data['verify_status']].'</td>';
		echo 	'<td>'.day_diff($data['application_date'],date("Y-m-d")).'</td>';
		echo 	'<td class="priceFormat">'.( $month_diff>=0 && $month_diff<$data['number_of_payments'] ? ($month_diff+1).'/'.$data['number_of_payments'] : $data['number_of_payments'] ).'</td>';

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