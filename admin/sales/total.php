<?php include_once("../library/sales/total.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	
    $('select[name="shop_id"]').change(
		function() {
			$('select[name="area_code"]').val("-1");
			$('select[name="block_code"]').val("-1");
			if ($(this).val() != -1) {
				$('select[name="area_code"]').prop("disabled", true);
			    $('select[name="block_code"]').prop("disabled", true);
			} else {
				$('select[name="area_code"]').prop("disabled", false);
			    $('select[name="block_code"]').prop("disabled", false);
			}
		}
	);
	$('select[name="block_code"]').change(
		function() {
			$('select[name="area_code"]').val("-1");
			$('select[name="shop_id"]').val("-1");
			if ($(this).val() != -1) {
				$('select[name="shop_id"]').prop("disabled", true);
				$('select[name="area_code"]').prop("disabled", true);
			} else {
				$('select[name="shop_id"]').prop("disabled", false);
				$('select[name="area_code"]').prop("disabled", false);
			}
		}
	);
	$('select[name="area_code"]').change(
		function() {
			$('select[name="block_code"]').val("-1");
			$('select[name="shop_id"]').val("-1");
			if ($(this).val() != -1) {
				$('select[name="block_code"]').prop("disabled", true);
				$('select[name="shop_id"]').prop("disabled", true);
			} else {
				$('select[name="block_code"]').prop("disabled", false);
				$('select[name="shop_id"]').prop("disabled", false);
			}
		}
	);
});
</script>

<style type="text/css">
#hoge {
    width:100%;
    position:absolute;
    top:160px;
    left:0;
}
#hogeInner {
    text-align: right;
    margin:0 0;
    padding: 0 23px;
}
</style>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			売上速報
			<span style="margin-left:20px;"><span style="font-size:15px;">
				<a href="./total.php?mode=display&pay_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&area_code=<?php echo $_POST['area_code'];?>&block_code=<?php echo $_POST['block_code'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./total.php?mode=display&pay_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&area_code=<?php echo $_POST['area_code'];?>&block_code=<?php echo $_POST['block_code'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				区分：<select name="type" style="height:25px;" ><?php Reset_Select_Key( $type , $_POST['type'] );?></select>
				<span style='<?php echo $authority_shop['id'] ? "display:none" : "" ?>'>
				    エリアコード：<select name="area_code" style="height:25px;" ><option value="-1">未選択</option><?php Reset_Select_Key( $area_code_list , $_POST['area_code'] ?? "-1" );?></select>
				    ブロック：<select name="block_code" style="height:25px;" ><option value="-1">未選択</option><?php Reset_Select_Key( $block_list , $_POST['block_code'] ?? "-1" );?></select>
                </span>
				店舗：<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:25px;margin-left:15px;" onClick="form.action='total.php';return true" />
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
			<div id="table-content" style="position: relative;">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="" style="width:100%;position: absolute;top: 50%;left: 50%;transform: translateY(-50%) translateX(-50%);-webkit-transform: translateY(-50%) translateX(-50%);">

			 <?php if ($type[$_POST['type']] && $_POST['type'] <= "9") echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:50px;">' . $type[$_POST['type']] .': ' . number_format($result_sum).' 円</div>';?>
			 <?php if ($type[$_POST['type']] && $_POST['type'] == "10") echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:50px;line-height: 60px;"><span style="font-size:30px;">' . $type[$_POST['type']] .' : クーリングオフ件数</span><br>' . $result_sum_9_2. '% : '.$result_sum_9_1. '件 </div>';?>
			 <?php if ($type[$_POST['type']] && $_POST['type'] == "11") echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:50px;line-height: 60px;"><span style="font-size:30px;">' . $type[$_POST['type']] .' (成約数 / CO数) : 成約単価</span><br>' . $result_sum3. '% (' . $result_sum2 . ' / '  . $result_sum .') : '.number_format($result_sum4).'円 </div>';?>		
			 <?php if ($type[$_POST['type']] && $_POST['type'] == "12") echo '<div style="text-align:center;color:#94b52c;font-weight:bold;font-size:50px;">' . $type[$_POST['type']] .': ' . number_format($result_sum).' 件</div>';?>	
				</form>

			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php //Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
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