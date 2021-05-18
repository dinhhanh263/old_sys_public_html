<?php include_once("../library/adcode/tag.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>タグ設置一覧 <a href="tag_edit.php" style="color:#94b52c;font-size:14px;">(新規)</a></h1>
	</div>
	<!-- end page-heading -->
	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">タグ名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">設置範囲</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">設置場所</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">広告ID</a></th>
					<th class="table-header-repeat line-left"><a href="">有効・無効</a></th>
					<th class="table-header-options line-left"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$gCoverage[$data['coverage']].'</td>';
		echo 	'<td>'.$gLocation[$data['location']].'</td>';
		echo 	'<td>'.($data['adcode'] ? $data['adcode'] : "") .'</td>';
		if($data['status'])echo '<td>'.$gStatus2[$data['status']].'</td>';
		else 			   echo '<td><font color="red">'.$gStatus2[$data['status']].'</font></td>';
		echo 	'<td class="options-width">';
		echo 		'<a href="tag_edit.php?id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="tag.php?action=delete&id='.$data['id'].'" onclick="return confirm(\'削除しますか？\')" title="削除" class="icon-2 info-tooltip"></a>';
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
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>