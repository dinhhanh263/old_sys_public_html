<?php include_once("../library/shop/index.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>店舗(部門)一覧 <a href="edit.php" style="color:#94b52c;font-size:14px;">(新規)</a></h1>
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
					<th class="table-header-repeat line-left"><a href="">店舗コード</a></th>
					<th class="table-header-repeat line-left"><a href="">店舗(部門)名</a></th>
					<th class="table-header-repeat line-left"><a href="">都道府県</a></th>
					<th class="table-header-repeat line-left"><a href="">公開・非公開</a></th>
					<th class="table-header-repeat line-left"><a href="">プレビュー</a></th>
					<th class="table-header-repeat line-left"><a href="">先行予約日</a></th>
					<th class="table-header-repeat line-left"><a href="">オープン日</a></th>
					<th class="table-header-options line-left"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['code'].'</td>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$gPref[$data['pref']].'</td>';
		echo 	'<td>'.$gStatus[$data['status']].'</td>';
		echo 	'<td><a href="'.$home_url.'shop/detail.php?preview=1&id='.$data['id'].'" target="_blank">プレビュー</a></td>';
		echo 	'<td>'.$data['rsv_date'].'</td>';
		echo 	'<td>'.$data['open_date'].'</td>';
		echo 	'<td class="options-width">';
		echo 		'<a href="edit.php?id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="index.php?action=delete&id='.$data['id'].'" onclick="return confirm(\'削除しますか？\')" title="削除" class="icon-2 info-tooltip"></a>';
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