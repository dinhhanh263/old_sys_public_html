<?php include_once("../library/adcode/blog.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>ブログ一覧 <a href="blog_edit.php" style="color:#94b52c;font-size:14px;">(新規)</a></h1>
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">日付</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">タイトル</a></th>
					<th class="table-header-repeat line-left"><a href="">公開状態</a></th>
					<th class="table-header-options line-left"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['blog_date'].'</td>';
		echo 	'<td>'.$data['title'].'</td>';
		echo 	'<td>'.$gStatus[$data['status']].'</td>';
		echo 	'<td class="options-width">';
		echo 		'<a href="blog_edit.php?id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="blog.php?action=delete&id='.$data['id'].'" onclick="return confirm(\'削除しますか？\')" title="削除" class="icon-2 info-tooltip"></a>';
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
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>