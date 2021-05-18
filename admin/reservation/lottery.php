<?php include_once("../library/reservation/lottery.php");?>
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
<div id="content"><!-- start content -->
	<div id="page-heading"><!--  start page-heading -->
		<h1>
			くじ当選一覧
			<span style="margin-left:20px;">
				<a href="./lottery.php?date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="date1" type="text" id="day" value="<?php echo $date1;?>" readonly  />~<input style="width:70px;height:21px;" name="date2" type="text" id="day2" value="<?php echo $date2;?>" readonly  />
				<a href="./lottery.php?date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select id="shop_id" name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<select name="prize" style="width:200px;height:25px;"><?php Reset_Select_Key( $prize_list , $_POST['prize'] );?></select>
				<select name="adcode" style="width:200px;height:25px;"><?php Reset_Select_Key( $adcode_list , $_POST['adcode'] );?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
			<?php if($authority_level<=6){?>
			<!--<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
				<input type='hidden' name="csv_pw" value="" />
			</span>-->
			<?php  }?>
		</h1>
		</form>
	</div><!-- end page-heading -->
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
			<div id="table-content"><!--  start table-content  -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<?php echo "<font color='red' size='-1'>".$gMsg.$_REQUEST['gMsg'] ."</font>";?>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">当選賞品</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">アドコード</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">媒体名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約コース</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約金額</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">最初アクセス日時</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">最終アクセス日時</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">アクセス回数</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">直接申込</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">登録日時</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">参照元</font></a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$name = $data['名前（カナ）'] ? $data['名前（カナ）'] : ( $data['名前'] ? $data['名前'] :'無名');

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['店舗'].'</td>';
		echo 	'<td>'.$data['会員番号'].'</td>';
		echo 	'<td title="'. $data['名前'].'">'.$name.'</td>';
		echo 	'<td>'.$data['当選賞品'].'</td>';
		echo 	'<td>'.$data['アドコード'].'</td>';
		echo 	'<td>'.$data['媒体名'].'</td>';
		echo 	'<td>'.$data['契約コース'].'</td>';
		echo 	'<td>'.number_format($data['契約金額']).'</td>';
		echo 	'<td>'.$data['契約日'].'</td>';
		echo 	'<td>'.$data['最初アクセス日時'].'</td>';
		echo 	'<td>'.$data['最終アクセス日時'].'</td>';
		echo 	'<td>'.$data['アクセス回数'].'</td>';
		echo 	'<td>'.$data['直接申込'].'</td>';
		echo 	'<td>'.$data['登録日時'].'</td>';
		echo 	'<td title="'.$data['参照元'].'">参照元</td>';
		echo '</tr>';
		$i++;
	}
}
?>
				</table><!--  end product-table................................... -->
				</form>
			</div><!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr>
      		<td>
      			<?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      		</td>
      	</tr>
      </table><!--  end paging................ -->
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
</div><!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>