<?php include_once('../library/adcode/index_mod.php');?>
<?php include_once('../include/header_menu.html');?>
 
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<h1>広告コード別集計
			<span style="margin-left:20px;">
				<a href="./?mode=display&access_date=<?php echo $pre_date?>&agent_id=<?php echo $_POST['agent_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="access_date" type="text" id="day" value="<?php echo $_POST['access_date'];?>" readonly />~<input style="width:70px;height:21px;" name="access_date2" type="text" id="day2" value="<?php echo $_POST['access_date2'];?>" readonly  />
				<a href="./?mode=display&access_date=<?php echo $next_date?>&agent_id=<?php echo $_POST['agent_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
                <span style="font-size:15px;">代理店：</span><select name="agent_id"  style="height:25px;"><option value="">全て</option><?php Reset_Select_Key($gAgent,$_POST['agent_id']); ?></select>
                <input type="hidden" name="mode" value="display" />
               	<input type="submit" value=" 表示 "  style="height:25px;" />
               	</form>
            </span>
        </h1>
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
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<!--<th class="table-header-repeat"><a href="./?sort=release_date&seq= DESC<?php echo $param; ?>">発行日</a></th>
					<th class="table-header-repeat"><a href="">広告ID</a></th>-->
					<th class="table-header-repeat"><a href="./?sort=adcode&seq= DESC<?php echo $param; ?>">広告コード</a></th>
					<th class="table-header-repeat line-left"><a href="./?sort=name&seq= DESC<?php echo $param; ?>">媒体名</a></th>
					<th class="table-header-repeat line-left"><a href="./?sort=agent&seq= DESC<?php echo $param; ?>">代理店名</a></th>
					<!-- <th class="table-header-repeat line-left"><a href="">android</a></th>
					<th class="table-header-repeat line-left"><a href="">iphone</a></th>
					<th class="table-header-repeat line-left"><a href="">ipad</a></th>
					
					<th class="table-header-repeat line-left"><a href="">pc</a></th>
					<th class="table-header-repeat line-left"><a href="">&nbsp;Click<br>&nbsp;(total)</a></th> -->
					<!--<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(本日)</a></th>-->
					<!--<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(昨日)</a></th>-->
					<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(total)</a></th>
					<!-- <th class="table-header-repeat line-left"><a href="">CVR</a></th> -->
					<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(今月)</a></th>
				</tr>
<?php
if ( $data ) {
  $i = 1;
  foreach ( $data as $key=>$val ) {
  	if(!$val['reg_all']) continue;
?>
					<tr <?php echo($val['del_flg'] ?  'bgcolor="#CCCCCC"' : ($i%2==0 ? '' : ' class="alternate-row"'))?>>
						<td><?php echo($val['adcode']); ?></td>
						<td><?php echo ($val['name']); ?></td>
						<td style="width:140px;"><?php echo($val['agent']); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php echo( $alarm_tab_begin . $val['reg_all'] .$cost_cnt. $alarm_tab_end ); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php echo($val['reg_month']); ?></td>
					</tr>
<?php
	$i++;
  }
}
?>
					<tr class="bgb" id="eee">
						<td colspan="3" id="ct">合計</td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($data['total_cv_cnt'] + 0); ?></td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['reg_month'] + 0); ?></td>
					</tr>
                   
				</table>
				<!--  end product-table................................... --> 
			</div>
			<!--  end content-table  -->
			
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