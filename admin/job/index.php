<?php include_once("../library/job/index.php");?>
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
			応募者一覧
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
    					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">年齢</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">生年月日</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">メールアドレス</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">希望店舗</a></th>
    					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a></th>
              <!-- <th class="table-header-repeat line-left minwidth-1"><a href="">卒業年</a></th> -->
    					<!-- <th class="table-header-repeat line-left minwidth-1"><a href="">媒体</a></th> -->
    					<th class="table-header-repeat line-left minwidth-1"><a href="">応募日時</a></th>
              <th class="table-header-repeat line-left minwidth-1"><a href="">媒体</a></th>
              <th class="table-header-repeat line-left minwidth-1"><a href="">きっかけ</a></th>
    					<th class="table-header-<?php echo ($authority_level<=1) ? "repeat" : "repeat" ?> line-left"><a href="">オプション</a></th>
    				</tr>
              <?php
                if ( $dRtn3->num_rows >= 1 ) {
                	$i = 1;
                	while ( $data = $dRtn3->fetch_assoc() ) {
                		echo  '<td>'.$data['entry_name_kana'].'&nbsp;'.$data['entry_name_kana_2'].'</td>';
                		echo 	'<td>'.$data['age'].'</td>';
                		echo 	'<td>'.($data['birthday']=="0000-00-00" ? "" : $data['birthday']).'</td>';
                		echo 	'<td>'.($data['now_tel_2'] ? $data['now_tel_2'] : $data['now_tel_1']).'</td>';
                		echo 	'<td>'.$data['now_email'].'</td>';
                		echo 	'<td>'.$shop_list[$data['shop_num']].'</td>';
                		echo 	'<td>'.($data['type']<6 ? $gGraduation[$data['type']] : $gSkill[$data['type']]).($gRecruitType[$data['type']]).'</td>';
                    // echo  '<td>'.(substr($data['graduation_ym'], 0,4)).'</td>';
                		// echo 	'<td>'.$adcode_list[$data['adcode']].'</td>';

                		echo 	'<td>'.$data['reg_date'].'</td>';
                    echo  '<td>'.$adcode_list[$data['adcode']].'</td>';
                    echo  '<td>'.$job_media_list[$data['job_media_id']].'</td>';
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
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>