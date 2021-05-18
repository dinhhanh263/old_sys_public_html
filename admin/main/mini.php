<?php include_once("../library/main/index.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>


<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="../js/jquery/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>


<![endif]>



<!-- Custom jquery scripts -->
<script src="../js/jquery/custom_jquery.js" type="text/javascript"></script>

<!-- Tooltips -->
<script src="../js/jquery/jquery.tooltip.js" type="text/javascript"></script>
<script src="../js/jquery/jquery.dimensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  $('a.info-tooltip ').tooltip({
    track: true,
    delay: 0,
    fixPNG: true,
    showURL: false,
    showBody: " - ",
    top: -35,
    left: 5
  });
});
</script>


<!--  date picker script -->
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

<script type="text/javascript" src="../js/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="../js/datepicker/ui.datepicker-ja.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // 時間ピッカー
    $("input#day,#day2").datepicker(
      {duration: "slow",dateFormat: 'yy-mm-dd'}
    );

  });
</script>
<style type="text/css">
  span.ui-datepicker-year {
    margin-right:1em;
  }
</style>


<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="../js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body>


<!-- start content-outer ........................................................................................................................START -->
<div >
<!-- start content -->
<div>

	<!--  start page-heading -->
	<div>
		<form name="search" method="post" action="">
    	<input name="start" type="hidden" id="start" value="0">
		  <div align="center">
		  	<a href="./mini.php?hope_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
			<input style="width:76px;height:25px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />
			<a href="./mini.php?hope_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
			<!-- <select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select> -->
			<select id="shop_id" name="shop_id" style="height:27px;" ><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
			<input type="submit" value=" 表示 "  style="height:25px;" />

		  </div>
		</form>
	</div>
	<!-- end page-heading -->

	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">

			<!--  start table-content  -->
			<div id="table-content" style="overflow: auto;">

				<!--  start product-table ..................................................................................... -->

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<td>Room</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">11</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">12</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">13</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">14</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">15</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">16</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">17</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">18</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">19</td>
					<td colspan="2" style="BORDER-LEFT: 3px solid">20</td>
				</tr>
<?php if ( $room_list ) {
	$i = 1;//横ライン

	foreach($room_list as $key => $room_name){
		$room_name = str_replace("カウンセリング", "C", $room_name);
		$room_name = str_replace("トリートメント", "T", $room_name);
		// if($_POST['shop_id']==9 && $key==35) $room_name = "HotPepper";

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$room_name.'</td>';
		$j = 1;//立ライン
		if(is_array($data[$key])){
		  $last_position = 1;
		  foreach($data[$key] as $sub_key => $sub_val){
		  	$space = $sub_val['hope_time']- $last_position;
		  	//前、中スペース
			for ($k=1; $k <= $space; $k++) {
				/*if( ($key==11 && $_POST['shop_id']>2 && $_POST['shop_id']<>6)  && $j<4){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif(( $key==11 && $_POST['shop_id']==1 && $_POST['hope_date']<='2014-08-10') && $j<4){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}else*/if( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date']) ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}else{
					echo '<td '.($j%2 ? 'style="BORDER-LEFT: 3px solid"' : '').' width="4%" ></td>';
				}
				$j++;
			}
			echo '<td '.($j%2 ? 'style="BORDER-LEFT: 3px solid"' : '').' colspan="'.$sub_val['length'].'" >☓</td>';
			$last_position = $sub_val['hope_time'] + $sub_val['length'];
			$j += $sub_val['length'];
		  }
		  //後ろスペース
		  for ($k=1; $k <= (21-$last_position); $k++) {
			/*if(($key==11 && $_POST['shop_id']>2 && $_POST['shop_id']<>6) && $j>17){
				echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif(($key==11 && $_POST['shop_id']==1 && $_POST['hope_date']<='2014-08-10') && $j>17){
				echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif(($_POST['shop_id']==5 && $_POST['hope_date']=='2014-09-10') && $j>16){ //大宮店：9/10　19:00以後達成会
							echo '<td style="background-color:black" width="4%" >☓</td>';
			}else*/if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $j>=$gPartyTime){ //第一木曜日　19:00以後達成会
							echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $j>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
			}else{
				echo '<td '.($j%2 ? 'style="BORDER-LEFT: 3px solid"' : '').' width="4%" ></td>';
			}
			$j++;
		  }

		}else{

			//全行空き
			for ($k=1; $k <= 20; $k++) {
				/*if(($key==11 && $_POST['shop_id']>2 && $_POST['shop_id']<>6) && ($k<4 || $k>17)){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif(($key==11 && $_POST['shop_id']==1 && $_POST['hope_date']<='2014-08-10') && ($k<4 || $k>17)){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif(($_POST['shop_id']==5 && $_POST['hope_date']=='2014-09-10') && $k>16){ //大宮店：9/10　19:00以後達成会
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}else*/if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ //第一木曜日　19:00以後達成会
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}else{
					echo '<td '.($j%2 ? 'style="BORDER-LEFT: 3px solid"' : '').' width="4%" ></td>';
				}
				$j++;
			}
		}
		echo '</tr>';
		$i++;
	}
}?>

				</table>
				<!--  end product-table................................... -->

			</div>
			<!--  end content-table  -->

		</div>
		<!--  end content-table-inner ............................................END  -->
	</div>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
