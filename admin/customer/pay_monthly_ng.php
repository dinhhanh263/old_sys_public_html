<?php include_once("../library/customer/pay_monthly_ng.php"); ?>
<?php include_once("../include/header_menu.html");?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

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
    img.onload = function() {
      document.search.action = "pay_monthly_ng_csv.php";
	  document.search.submit();
	  return fales;
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
			月額NG一覧
			<span style="margin-left:20px;">
				<a href="./pay_monthly_ng.php?ym_from=<?php echo $pre_ym?>&ym_to=<?php echo $pre_ym?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&type=<?php echo $_POST['type']?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:60px;height:21px;" name="ym_from" type="text"  class="ympicker" value="<?php echo $_POST['ym_from'];?>" readonly  />~
				<input style="width:60px;height:21px;" name="ym_to" type="text"  class="ympicker" value="<?php echo $_POST['ym_to'];?>" readonly  />
				<a href="./pay_monthly_ng.php?ym_from=<?php echo $next_ym?>&ym_to=<?php echo $next_ym?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&type=<?php echo $_POST['type']?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
                                <select name="search_shop_id" style="height:25px;" ><option>-</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['search_shop_id'] ? $_POST['search_shop_id'] : "", $gArea_Group, "area_group" );?></select>
                                <select name="course" style="height:25px;"><?php Reset_Select_Key( $gCourseType3 , $_POST['course'] );?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" /> <!--CSV export-->
			</span>
			<?php if($authority_level<=1){?>
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
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">NGパターン</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客カナ</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約ステータス</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">入金金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">何年</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">何月分</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">予約日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a></th>
				</tr>
<?php
if ( $list ) {
	$i = 1;//横ライン
	
	foreach($list as $key => $val){
		if($val['customer_id']){
		$hope_date = Get_Table_Col("reservation","hope_date"," WHERE del_flg=0 AND customer_id=".$val['customer_id']." AND type=2 AND hope_date>='".date('Y-m-d')."' ORDER BY hope_date LIMIT 1");
		}else{
			$hope_date = "";
		}
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$val['ng'].'</td>';
		echo 	'<td>'.$val['no'].'</td>';
		echo 	'<td title="'.$val['name'].'">'.($val['name_kana'] ? $val['name_kana'] : ($val['name'] ? $val['name'] : '無名')).'</td>';
		echo 	'<td>'.$val['status'].'</td>';
		echo 	'<td>'.$val['course_name'].'</td>';
		echo 	'<td>'.number_format($val['pay_amount']).'</td>';
		echo 	'<td>'.$val['option_date'].'</td>';
                $option_year = "";
                $option_month = "";
                if($val['option_year']) {
                    $option_year = $val['option_year'].'年';
                }
                echo 	'<td>'.$option_year.'</td>';
                if($val['option_month']) {
                    $option_month = $val['option_month'].'月分';
                }
 		echo 	'<td>'.$option_month.'</td>';
 		echo 	'<td>'.$hope_date.'</td>';
 		echo 	'<td>'.$val['contract_date'].'</td>';
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

