<?php include_once("../library/reservation/cancel_list.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			新規キャンセル一覧
			<span style="margin-left:20px;">
				<a href="./cancel_list.php?hope_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" />~<input style="width:70px;height:21px;" name="hope_date2" type="text" id="day2" value="<?php echo $_POST['hope_date2'];?>" />
				<a href="./cancel_list.php?hope_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<!-- <select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select> -->
        <select id="shop_id" name="shop_id" style="height:25px;" ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" />
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
				<?php if($authority_level<=6 || $authority['id']==106){?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
				<?php } ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">来店日時</a></th>
					<th class="table-header-options line-left"><a href="">オプション</a></th>
				</tr>
<?php
if ($dRtn3) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($data['customer_id'])."'");
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$gResType[$data['type']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.$data['name'].'</td>';
	if($authority_level<=6 || $authority['id']==106){
		echo 	'<td>'.$data['tel'].'</td>';
	}
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td>'.$data['hope_date'].' '.$gTime2[$data['hope_time']].'</td>';
		echo 	'<td class="options-width">';
		echo 		'<a href="edit.php?reservation_id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
		//echo 		'<a href="index.php?action=delete&id='.$data['id'].'" onclick="return confirm(\'キャンセルしますか？\')" title="キャンセル" class="icon-2 info-tooltip"></a>';
		// echo 		'<a href="index.php?action=send&id='.$data['id'].'&shop_id='.$data['shop_id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
		// echo 		'<a href="edit.php?action=new&type=2&customer_id='.$data['customer_id'].'&shop_id='.$data['shop_id'].'" onclick="return confirm(\'次の予約をしますか？\')" title="次予約詳細" class="icon-5 info-tooltip"></a>';
		//echo 		'&nbsp;<a href="../sales/reg_detail.php?action=new&customer_id='.$data['customer_id'].'&shop_id='.$data['shop_id'].'" onclick="return confirm(\'レジ精算に移動しますか？\')" title="レジ精算" class="icon-4 info-tooltip"></a>';
		echo 	'</td>';
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