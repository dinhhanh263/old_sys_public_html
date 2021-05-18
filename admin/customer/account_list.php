<?php include_once("../library/customer/account_list.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">
function display() {
    document.search.action = "account_list.php";
    document.search.submit();
	return fales;
}
</script>

<script type="text/javascript">
function csv_export() {
    var name=prompt("パスワードを入力して下さい。", "");
    var password="exsKOWON17wP";
    if (name==password) {
    	document.search.action = "csv_account.php";
    	document.search.submit();
		return fales;
	}else{
      alert("パスワードが正しくありませんでした。");
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
			バーチャル口座一覧
			<span style="margin-left:20px;">
				<span style="font-size:15px;">付与日</span>
				<a href="./account_list.php?give_date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="give_date" type="text" id="day" value="<?php echo $_POST['give_date'];?>" readonly />~
				<input style="width:70px;height:21px;" name="give_date2" type="text" id="day2" value="<?php echo $_POST['give_date2'];?>" readonly />
				<a href="./account_list.php?give_date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="branch_no" style="height:25px;width:130px;" ><?php Reset_Select_Key( $virtualBankBranchNo , $_POST['branch_no'] );?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 " onclick='display();'  style="height:25px;" /> <!--CSV export-->
			</span>
			<span style="float:right; margin-right:25px;">
				<input type='button' value=' CSV ' onclick='csv_export();' style="height:25px;" />
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">メールアドレス</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支店名</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支店番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">バーチャル口座番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">付与日時</a></th>
					<!--<th class="table-header-repeat line-left"><a href="">オプション</a></th>-->
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td title="'.$data['name'].'"><a href="/admin/customer/index.php?customer_id='.$data['customer_id'].'" target="_blank">'.($data['name_kana'] ? $data['name_kana'] : $data['name']).'</td>';
		echo 	'<td>'.$data['tel'].'</td>';
		echo 	'<td>'.$data['mail'].'</td>';
		echo 	'<td>'.$data['branch_name'].'</td>';
		echo 	'<td>'.$data['branch_no'].'</td>';
		echo 	'<td>'.$data['virtual_no'].'</td>';
		echo 	'<td>'.$data['give_date'].'</td>';
		//echo 	'<td><a  rel="facebox" href="mini.php?id='.$data['customer_id'].'">30日Tel◎ 60日Tel☓ 80日Tel- </a></td>';
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