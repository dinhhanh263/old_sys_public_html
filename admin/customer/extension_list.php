<?php include_once("../library/customer/extension_list.php");?>
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
			保証期間延長申請一覧
			<span style="margin-left:20px;">
				<a href="./extension_list.php?extension_edit_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="extension_edit_date" type="text" id="day" value="<?php echo $_POST['extension_edit_date'];?>" />~<input style="width:70px;height:21px;" name="extension_edit_date2" type="text" id="day2" value="<?php echo $_POST['extension_edit_date2'];?>" />
				<a href="./extension_list.php?extension_edit_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
        <select name="search_shop_id" style="height:25px;" ><option>-</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['search_shop_id'] ? $_POST['search_shop_id'] : "", $gArea_Group, "area_group" );?></select>
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
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a></th>
          <th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a></th>
          <th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約日</a></th>
          <th class="table-header-repeat line-left minwidth-1"><a href="">施術保証終了日</a></th>
          <th class="table-header-repeat line-left minwidth-1"><a href="">申請日</a></th>
          <?php if($authority_level<1){ ?>
					<th class="table-header-repeat line-left"><a href="">取消</a></th>
          <?php } ?>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		if(!$data['status']){
		echo 	'<td>'.($data['id'] ? $gContractStatus[$data['status']] : "").'</td>';
		}else{
		echo 	'<td><font color="red">'.$gContractStatus[$data['status']].'</font></td>';
		}
    echo  '<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td title="'.$data['name'].'">'.($data['name_kana'] ? $data['name_kana'] : $data['name']).'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td>'.$data['contract_date'].'</td>';
    echo  '<td>'.$data['end_date'].'</td>';
    echo  '<td>'.$data['extension_edit_date'].'</td>';
		if($authority_level<1){
      echo    '<td><a href="extension_list.php?action=delete&id='.$data['id'].'&end_date='.$data['end_date'].'" onclick="return confirm(\'延長申請を取消しますか？\')" title="延長取消" class="icon-2 info-tooltip"></a></td>';
    };
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