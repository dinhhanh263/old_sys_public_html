<?php include_once("../library/main/shift.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SHIFT TABLE</title>
<link rel="shortcut icon" href="../images/favicon.ico" />
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

		  	<a href="./shift.php?shift_month=<?php echo $pre_month?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前月" /></a>
			<input style="width:56px;height:25px;" name="shift_month" type="text" value="<?php echo substr($_POST['shift_month'],0,7);?>" readonly  />
			<a href="./shift.php?shift_month=<?php echo $next_month?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌月" /></a>
			<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
			<input type="submit" value=" 表示 "  style="height:25px;" />

		  </div>
		</form>
	</div>
	<!-- end page-heading -->

	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">

			<!--  start table-content  -->
			<div id="table-content" class="shift-content">

				<!--  start product-table ..................................................................................... -->
       			<span class="shift-icon">
    		<?php
            // 本部（営業部用）
			if($_POST['shop_id']=="1001"){
				echo "本：本部（営業部用）、☓：休み、★：希望休日、特休：夏季休暇・誕生日休暇、代：代休、イ：イベント、半前：半日勤務（午前）、半後：半日勤務（午後）、欠：当日欠勤、刻：遅刻、退：早退、有：有給、忌：忌引、半：半日(年末)、結婚：結婚休暇、CC：コールセンター、本研：本社研修センター、大研：大阪研修センター<br />";
				foreach($alias_list as $key=>$val ) echo "<span class='shift-icon01'>".$key."：".$val."</span>" ;
			// コールセンター
			}elseif($_POST['shop_id']=="999"){
				echo "※ C早:CC早番(10:30~19:30)、C中:CC中番(11:00~20:00)、C遅:CC遅番(12:00~21:00)、C2:CC遅番2(12:30~21:30)、C通:CC通し(11:00~21:00)<br />";
				echo "※ ☓：休み、★：希望休日、特休：夏季休暇・誕生日休暇、代：代休、イ：イベント、半前：半日勤務（午前）、半後：半日勤務（午後）、有：有給、忌：忌引、半：半日(年末)<br />";
				echo "※ 欠：当日欠勤、刻G:遅刻(C早)、退G:早退(C早)、刻H:遅刻(C中)、退H:早退(C中)、刻I:遅刻(C遅)、退I:早退(C遅)、刻J:遅刻(C2)、退J:早退(C2)、刻K:遅刻(C通)、退K:早退(C通)";
			// 研修センターと店舗
			}else{
				echo "※ 早：早番(10:30~19:30)、中：中番(11:00~20:00)、メ遅：メンズ遅(12:30~21:30)、メ通：メンズ通し(11:45~21:15)、メ早：メンズ早(11:30~20:30)、旧メ遅：旧メンズ遅(12:00~21:00)<br />";
				echo "※ ☓：休み、★：希望休日、特休：夏季休暇・誕生日休暇、イ：イベント、半前：半日勤務（午前）、半後：半日勤務（午後）、有：有給、忌：忌引、半：半日(年末)、結婚：結婚休暇<br />";
				echo "※ 欠：当日欠勤、刻B：遅刻（早番）、退B：早退（早番）、刻C：遅刻（中番）、退C：早退（中番）、刻D：遅刻（メンズ遅）、退D：早退（メンズ遅）、刻L:遅刻(メ通)、退L:早退(メ通)、
						刻M：遅刻（メンズ早）、退M：早退（メンズ早）、刻N：遅刻（旧メンズ遅）、退N：早退（旧メンズ遅）";
			}
			?>
        		</span>
				<table border="0"  cellpadding="0" cellspacing="0" id="product-table" class="shift-table">
				<tr>
					<td class="shift-th">名前</td>
					<?php for($i=1;$i<=$days;$i++){
						echo '<td style="text-align:center;padding:0px;">'.$i.'<br>'.getYobi($current_month.'-'.$i,3).'</td>';

					}?>
					<td colspan="3" style="text-align:center;">新規</td>
				</tr>
				<tr>
                  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm">
                   <input name="action" type="hidden" value="new">

                   <td  class="shift-th"><select name="staff_id" class="select-noarrow2" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$end_date2 ) , "",getDatalist5("shop",$_POST['shop_id']));?></select></td>

                   <?php for($i=1;$i<=$days;$i++){
						echo '<td style="text-align:center;padding: 0px;"><select name="day'.$i.'" class="select-noarrow">';
						Reset_Select_Key( $gShiftType ,"" ) ;
						echo '</select></td>';
					}?>
                   <td colspan="3" style="text-align:center;padding: 5px;"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="新規" class="icon-1 info-tooltip"></a></td>
                   <input name="shop_id" type="hidden" value="<?php echo($_POST['shop_id']); ?>">
                    <input name="shift_month" type="hidden" value="<?php echo($_POST['shift_month']); ?>">
                  </form>
              	</tr>
              	<tr><td colspan="<?php echo ($days+4);?>" class="shift-title"><h2><?php echo $shop_list[$_POST['shop_id']]?> 勤務シフト表</h2></td></tr>
              	<tr>
					<td class="shift-th">名前</td>
					<?php for($i=1;$i<=$days;$i++){
						echo '<td>'.$i.'<br>'.getYobi($current_month.'-'.$i,3).'</td>';
					}?>
					<td style="text-align:center;padding:0px;">日数</td>
					<td style="text-align:center;padding:0px;">変更</td>
					<td style="text-align:center;padding:0px;">削除</td>
				</tr>

<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1 ;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$i++;
		if($staff_type[$data['staff_id']]==19) $background_color = "purple";
		elseif($staff_type[$data['staff_id']]==21) $background_color = "gray";
		else $background_color = "";
?>
    		<tr<?php echo($i % 7 == 0 ? ' class="shift-10"' : '')?>>
			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo $i;?>">
        	    <td class="shift-th" style="background-color:<?php echo $background_color ;?>;"><select name="staff_id" class="select-noarrow2"><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$end_date2) , $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>

  	    <?php for($j=1;$j<=$days;$j++){
					$day = "day".$j;
          if($j % 10 == 0){
            echo '<td class="shift-th" style="background-color:'.(in_array($data[$day], $notice) ? "red" : "" ).'"><select name="'.$day.'" class="select-noarrow">';
            Reset_Select_Key( $gShiftType ,$data[$day] ) ;
            echo '</select></td>';
            if(in_array($data[$day], $work)) {
              $cnt[$i] += 1;
              $total[$j] += 1;
            }
          }else{
            echo '<td style="background-color:'.(in_array($data[$day], $notice) ? "red" : "" ).'"><select name="'.$day.'" class="select-noarrow">';
            Reset_Select_Key( $gShiftType ,$data[$day] ) ;
            echo '</select></td>';
            if(in_array($data[$day], $work)) {
              $cnt[$i] += 1;
              $total[$j] += 1;
            }
          }
				}?>
				<td><?php echo $cnt[$i];?></td>
				<!--詳細編集-->
				<td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a>
				<input name="action" type="hidden" value="edit">
				<input name="id" type="hidden" value="<?php echo($data['id']); ?>">
				<input name="shop_id" type="hidden" value="<?php echo($_POST['shop_id']); ?>">
                <input name="shift_month" type="hidden" value="<?php echo($_POST['shift_month']); ?>">
 	  		</form>
				<!--削除-->
	    	<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="dfrm<?php echo $i;?>">
				<td><a href="javascript:document.forms['dfrm<?php echo $i;?>'].submit();" onclick="return confirm('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a></td>
				<input name="action" type="hidden" value="delete">
				<input name="id" type="hidden" value="<?php echo($data['id']); ?>">
				<input name="shop_id" type="hidden" value="<?php echo($_POST['shop_id']); ?>">
                <input name="shift_month" type="hidden" value="<?php echo($_POST['shift_month']); ?>">
  			</form>
			</tr>
<?php
	} //while
}
?>
			<tr>
				<td class="shift-th">勤務人数</td>
				<?php for($j=1;$j<=$days;$j++) echo '<td style="text-align:center;">'.$total[$j].'</td>' ;?>
				<td colspan="3"></td>
			</tr>

				</table>

				<!--  end product-table................................... -->

			</div>
			<!--  end content-table  -->

		</div>
		<!--  end content-table-inner ............................................END  -->
	</div>
<?php if($authority_level<=6){?>
	<div style="margin:20px;">
		<form name="form_csv" method="post" action="shift_export.php">
		  <div align="center">
			<!--<input style="width:76px;height:25px;margin-right:10px;" name="shift_date" type="text" id="day" value="<?php echo date('Y-m-d');?>" readonly  />-->
			<a href="./shift.php?shift_month=<?php echo $pre_month?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前月" /></a>
			<input style="width:56px;height:21px;" name="shift_month" type="text" value="<?php echo substr($_POST['shift_month'],0,7);?>" readonly  />
			<a href="./shift.php?shift_month=<?php echo $next_month?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌月" /></a>

			<select name="shop_id" style="height:25px;margin-right:10px;" ><option value=''>全て</option><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
			<input type="submit" value=" エクスポート "  style="height:25px;" />

		  </div>
		</form>
	</div>
	<div class="clear">&nbsp;</div>
	<p>CSV形式：勤務日,従業員コード,スケジュールパターンコード,出勤所属コード</p>
<?php }?>
</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
