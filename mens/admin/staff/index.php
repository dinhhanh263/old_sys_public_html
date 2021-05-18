<?php include_once("../library/staff/index.php");?>
<?php include_once("../include/header_menu.html");?>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>従業員一覧 
		<?php // <a href="edit.php" style="color:#94b52c;font-size:14px;">(新規)</a> ?>
			<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
			<select name="type" style="height:25px;" ><?php Reset_Select_Key( $gStaffType , $_POST['type'] );?></select>
			<select name="class" style="height:25px;" ><?php Reset_Select_Key( $gClass , $_POST['class'] );?></select>			
			<select name="status" style="height:25px;" ><?php Reset_Select_Key( $gStatus3 , $_POST['status'] );?></select>
			<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
			<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='index.php';return true" />
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
				<!--  start staff-all ..................................................................................... -->
				<div id="staff-all">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<!--ソート項目用のパラメータ-->
				<?php $sort_param ='&shop_id='.$_POST['shop_id'].'&type='.$_POST['type'].'&class='.$_POST['class'].'&status='.$_POST['status'].'&line_max='.$_POST['line_max']; ?>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 1; echo $sort_param ?>">従業員No</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 2; echo $sort_param ?>">名前</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 3; echo $sort_param ?>">入社日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 4; echo $sort_param ?>">所属</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 5; echo $sort_param ?>">役職</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 6; echo $sort_param ?>">期別</a></th>
					<th class="table-header-repeat line-left"><a href="?s=<?php echo $base + 7; echo $sort_param ?>">公開・非公開</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 8; echo $sort_param ?>">合計星数</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="?s=<?php echo $base + 9; echo $sort_param ?>">アクティブ星数</a></th>
					<th class="table-header-options line-left"><a href="">オプション</a></th>
				</tr>
<?php
// ページ情報のパラメータ
$param  = '&start='.$_POST['start'].'&keyword='.$_POST['keyword'].'&shop_id='.$_POST['shop_id'].'&type='.$_POST['type'].'&class='.$_POST['class'].'&status='.$_POST['status'].'&line_max='.$_POST['line_max'];
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
	// 非公開時に行の色を変える
	$closedFlg = ($data['status'] == 1 ) ? 'closed' : '';
	// 非公開時に文字の色を変える
	$closedStrType = ($data['type'] == 0 ) ? 'd_none' : '';
	$closedStrClass = ($data['class'] == 0 ) ? 'd_none' : '';


		echo '<tr'. ( $i%2==0 ? ' class="alternate-row '.$closedFlg.'"' : ' class="'.$closedFlg.'"' ) .'>';
		echo 	'<td>'.$data['code'].'</td>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$data['begin_day'].'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td class="'.$closedStrType.'">'.$gStaffType[$data['type']].'</td>';
		echo 	'<td class="'.$closedStrClass.'">'.$gClass[$data['class']].'</td>';
		echo 	'<td>'.$gStatus[$data['status']].'</td>';
		echo 	'<td>'.$data['total_stars'].'</td>';
		echo 	'<td>'.$data['active_stars'].'</td>';
		echo 	'<td class="options-width">';
		// echo 		'<a href="edit.php?id='.$data['id'].$param.'" title="詳細" class="icon-1 info-tooltip"></a>';
		//echo 		'<a href="index.php?action=delete&id='.$data['id'].'&line_max='.$_POST['line_max'].'&shop_id='.$_POST['shop_id'].'" onclick="return confirm(\'削除しますか？\')" title="削除" class="icon-2 info-tooltip"></a>';
		//echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'ログイン情報を送信しますか？\')" title="ログイン情報送信" class="icon-3 info-tooltip"></a>';

		echo 	'</td>';
		echo '</tr>';
		$i++;
	}
}
?>
				
				</table>
				<!--  end product-table................................... --> 
				</form>
			<!--  end staff-all................................... --> 
			</div>
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