<?php include_once("../library/service/loan_applist.php");?>
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
    document.search.action = "virtual_no_list.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function cic_export () {
    document.search.action = "loan_app_cic_export.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function csv_export () {
    document.search.action = "loan_app_csv_export.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function verify_complete () {
    document.search.action = "loan_app_verify_complete_export.php";
    document.search.submit();
	return fales;
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>
			バーチャル口座一覧
			<span style="margin-left:20px;">
				<a href="./virtual_no_list.php?application_date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="application_date" type="text" id="day" value="<?php echo $_POST['application_date'];?>" readonly />~
				<input style="width:70px;height:21px;" name="application_date2" type="text" id="day2" value="<?php echo $_POST['application_date2'];?>" readonly />
				<a href="./virtual_no_list.php?application_date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>

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
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">バーチャル口座番号</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">会員番号</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">名前</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">電話番号</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">コース名</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">振込日</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">入金結果</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">過不足金</a></font></th>
					<th class="table-header-repeat line-left minwidth-1"><font size="-1"><a href="">連絡状況</a></font></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	$virtual_no = 9500250000;
	while ( $data = mysql_fetch_assoc($dRtn3) ) {

		echo '<tr'. ( $data['payment_loan']<=0 ? ' style="background-color: yellow;"' : ($i%2==0 ? ' class="alternate-row"' : '') ) .'>';
		echo 	'<td>'.$virtual_no.'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$data['tel'].'</td>';
		echo 	'<td>全身33ヵ所パック(SPプラン)</td>';
		echo 	'<td>2018-04/20</td>';
		echo 	'<td>振込済</td>';
		echo 	'<td>0</td>';
		echo 	'<td>無し</td>';
		echo '</tr>';
		$i++;
		$virtual_no++;
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