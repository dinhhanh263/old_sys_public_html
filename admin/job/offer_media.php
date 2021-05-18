<?php include_once("../library/job/offer_media.php");?>
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
			求人媒体一覧
      <a href="offer_media_edit.php" style="color:#94b52c;font-size:14px;">(新規)</a>
			<span style="margin-left:20px;">
				<!-- <a href="./?reg_date2=<?php echo $pre_date?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a> -->
				<!-- <input style="width:70px;height:21px;" name="reg_date" type="text" id="day" value="<?php echo $_POST['reg_date'];?>" readonly />~<input style="width:70px;height:21px;" name="reg_date2" type="text" id="day2" value="<?php echo $_POST['reg_date2'];?>" readonly  /> -->
				<!-- <a href="./?reg_date2=<?php echo $next_date?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a> -->
				<!-- <select name="adcode" style="height:25px;width:100px;" ><?php Reset_Select_Key( $job_media_list , $_POST['adcode'] );?></select> -->
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
		</h1>
		</form>
	</div>

	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!-- end page-heading -->
      	<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
        	<tr><td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
        </table>
        <!--  end paging................ -->
  			<!--  start product-table ..................................................................................... -->
  			<form id="mainform" action="">
  				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
    				<tr>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">媒体ID</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">媒体名</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">公開/非公開</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">求人種類</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">公開開始日</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">公開終了日</a></th>
              <th class="table-header-repeat line-left minwidth-1"><a href="">詳細</a></th>
    				</tr>
              <?php
                if ( $dRtn3->num_rows >= 1 ) {
                	$i = 1;
                	while ( $data = $dRtn3->fetch_assoc() ) {
                		echo 	'<td>'.$data['id'].'</td>'; //媒体ID
                		echo 	'<td>'.$data['name'].'</td>'; //媒体名
                		echo 	'<td>'.($data['status']==0 ? "公開":"非公開").'</td>'; //公開/非公開
                		echo 	'<td>'.$data['type'].'</td>'; //求人種類
                		echo 	'<td>'.$data['start_date'].'</td>'; //公開開始日
                		echo 	'<td>'.$data['end_date'].'</td>'; //公開終了日
                    echo  '<td style="width:140px;">';
                    echo    '<a href="offer_media_edit.php?id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
                    echo    '<a href="offer_media.php?action=delete&id='.$data['id'].'&keyword='.$_POST['keyword'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-2 info-tooltip"></a>';
                    echo  '</td>';
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
  				<td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td>
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