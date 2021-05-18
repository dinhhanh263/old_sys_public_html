<?php include_once("../library/muryou/index.php");?>
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
			無料会員一覧
			<span style="margin-left:20px;">
				<a href="./?reg_date2=<?php echo $pre_date?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="reg_date" type="text" id="day" value="<?php echo $_POST['reg_date'];?>" readonly />~<input style="width:70px;height:21px;" name="reg_date2" type="text" id="day2" value="<?php echo $_POST['reg_date2'];?>" readonly  />
				<a href="./?reg_date2=<?php echo $next_date?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="adcode" style="height:25px;width:100px;" ><?php Reset_Select_Key( $adcode_list , $_POST['adcode'] );?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
		</h1>
		</form>
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
				<!-- end page-heading -->
	<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr><td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">年齢</a></th>
<?php /* ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">生年月日</a></th>
<?php */ ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">メールアドレス</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">広告媒体</a></th>
<?php /* ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">プレゼント</a></th>					
<?php */ ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">登録状況</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">登録日</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td>'.$data['age'].'</td>';
//		echo 	'<td>'.($data['birthday']=="0000-00-00" ? "" : $data['birthday']).'</td>';		
		echo 	'<td>'.$data['tel'].'</td>';
		echo 	'<td>'.$data['mail'].'</td>';
		echo 	'<td>'.$adcode_list[$data['adcode']].'</td>';
//		echo 	'<td>'.$gPresent[$data['present']].'</td>';
		echo 	'<td>'.$gReg_flg[$data["reg_flg"]].'</td>';
		echo 	'<td>'.$data['reg_date'].'</td>';
		echo 	'<td style="width:140px;">';
		echo 		'<a href="edit.php?id='.$data['id'].'" title="詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="index.php?action=delete&id='.$data['id'].'&keyword='.$_POST['keyword'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-2 info-tooltip"></a>';
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
				<td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td>
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