<?php include_once("../library/reservation/edit_reservation.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>予約一覧</h1>
	</div>
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
				<form id="mainform" method="post" action="" enctype="multipart/form-data">
					<input type="hidden" name="action" value="edit" />
					<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<tr>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前(カナ)</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">予約店舗</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">予約日</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">予約時間</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">登録日時</font></a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">変更前コース名</font></th>
						<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">変更後コース名</font></a></th>
					</tr>
					<?php
					if ( $dRtn->num_rows >= 1 ) {
						$i = 1;
						while ( $reservationInfo = $dRtn->fetch_assoc() ) {
							echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ).'>';
							echo 	'<td>'.$customer['no'].'</td>';
							echo 	'<td>'.$customer['name'].'</td>';
							echo 	'<td>'.$customer['name_kana'].'</td>';
							echo 	'<td>'.$reservationInfo['name'].'</td>';
							echo 	'<td>'.$reservationInfo['hope_date'].'</td>';
							echo 	'<td>'.$gTime[$reservationInfo['hope_time']].'</td>';
							echo 	'<td>'.$reservationInfo['reg_date'].'</td>';
							echo    '<td>'. $reservationInfo['course_name'] . '</td>';
							echo    '<td><select name="reservation_contract_id[]">';
							Reset_Select_Key($course_list , $reservationInfo['contract_id']);
							echo    '</select></td>';
							echo '</tr>';
							echo '<input name="reservation_id[]" type="hidden" value="'.$reservationInfo['id'].'" />';
							$i++;
						}
					}
					?>
					</table>
					<!--  end product-table................................... -->
					<div style="float:right;"> 
						<input type="submit" value="" class="form-submit" onclick="return conf1('');"/>
					</div>
				</form>
			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
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