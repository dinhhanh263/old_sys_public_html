<?php include_once("../library/main/index.php");?>
<?php include_once("../library/main/shift_d2.php");?>
<?php include_once("../include/header_menu.html");?>
 <link rel="stylesheet" href="../css/colorbox.css" />

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>
<script language="JavaScript">
<!--
function whereTo(url,staff,time){
	var isConfirmed=confirm(time + 'から60分休憩にしますか？')
	if(isConfirmed){
		window.open(url);
		window.open(window.location, '_self').close();
	}else if (confirm(time + 'から30分休憩にしますか？')){
		var url2 = url + "&length=1";
		window.open(url2);
		window.open(window.location, '_self').close();
	}
}
//-->
</script> 
<script language="JavaScript">
<!--
function whereTo2(url,staff,time){
	var isConfirmed=confirm('休憩を取消しますか？')
	if(isConfirmed){
		window.open(url);
		window.open(window.location, '_self').close();
	}
}
//-->
</script> 
<script language="JavaScript">
<!--
function whereTo3(url,staff,time){
	if (confirm(time + 'から30分休憩にしますか？')){
		var url2 = url + "&length=1";
		window.open(url2);
		window.open(window.location, '_self').close();
	}
}
//-->
</script> 
 <script src="../js/jquery.jPrintArea.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.link').click(function(){ $.jPrintArea('#mainform') });
});
</script> 

 <script src="../js/jquery.jPrintArea.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.link').click(function(){ $.jPrintArea('#content-table-inner') });
});
</script> 
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<form name="search" method="post" action="">
		<h1><?php echo $_POST['shop_id']=="999" ? "休憩" : "予約";?>情報
		  <span style="margin-left:20px;">
		  	<a href="./staff.php?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $pre_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
			<input style="width:76px;height:25px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />
			<a href="./staff.php?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $next_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<select name="shop_id" style="height:27px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'] );?></select>
			<input type="submit" value=" 表示 "  style="height:25px;" />
			
			<span style="float:right;margin-right:25px;padding-bottom:5px">
				<?php if($authority_level<20){?>
				<input type="button" value=" ルーム別予約表 "  style="height:25px;"  onclick="window.open('./index.php?shop_id=<?php echo $_POST['shop_id']==999 ? 1 : $_POST['shop_id'] ;?>&hope_date=<?php echo $_POST['hope_date'];?>');"/>
				<input type="button" value=" シフト表(月) "  style="height:25px;"  onclick="window.open('./shift.php?shop_id=<?php echo$_POST['shop_id'];?>&shift_month=<?php echo substr($_POST['hope_date'],0,7);?>');"/>
				<?php }?>
				<input type="button" value=" 印刷 "  style="height:25px;" class="link"/>
			</span>
		  </span>
		</h1>
		<?php if($authority_level<20){?>
		<div style="padding-top:15px;padding-bottom:-10px"><iframe src="shift_d2.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>" height="40" width="98%"></iframe></div>
		<?php }?>
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
			<div id="table-content">

				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
					<div align="center"><h1 style="color:<?php echo $shop_color;?>;"><?php echo getYobi($_POST['hope_date'],1);?><?php echo $shop_list[$_POST['shop_id']];?><!-- <font size="-1">(<a rel="facebox" href="shift_d.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>">シフト表</a>)</font>--></h1>
					<?php if($_POST['hope_date'] == date("Y-m-d")){?>
						<span style="color:blue;font-weight:bold;">当日予約：前日21時から当日21時までブルー文字</span>
					<?php } ?>
					</div>

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table" style="table-layout: fixed;">
				<tr id="room">
					<th class="table-header-repeat line-left minwidth-1"><a href="">担当名</a> </th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">11</a>	</th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">12</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">13</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">14</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">15</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">16</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">17</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">18</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">19</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">20</a></th>
				</tr>
<?php if ( $staff_current ) {
	$i = 1;
	$cnt = array();
	$param = '?action=rest&shop_id='.$_POST['shop_id'].'&hope_date='.$_POST['hope_date'].'&hope_time=';
	foreach($staff_current as $key => $staff_name){
		$url = "./rest.php";

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td width="5.5%">'.$staff_name.'</td>';
		if(is_array($staff_data[$key])){
		   
			$volume = array();
		  
		  	//時間順ソート
		  	foreach($staff_data[$key] as $key111 => $row111) {
  		  	  	$volume[$key111]  = $row111['hope_time'];
		  	}
		  	array_multisort($volume, SORT_ASC, $staff_data[$key]);
		  
		  	$last_position = 1;
		  	$gtimes = 1; //
		  
		  	foreach($staff_data[$key] as $sub_key => $sub_val){
		  		//来店なしの除外
		  		if($sub_val['status0']==1) continue;
		  		if(!$sub_val['hope_time']) continue;

		  		//集計がカウンセリングだけ
		  		if($sub_val['type']==1)$cnt[$sub_val['status0']] +=1;

		  		if($_POST['keyword']){
		  			$search_color = ( strstr($sub_val['name'], $_POST['keyword']) || strstr($sub_val['name_kana'], $_POST['keyword'])) ? ' style="color:orange;font-weight:bold;"' : '';
		  		}elseif($_POST['id']){
		  			$search_color = ( $sub_val['id'] ==$_POST['id']) ? ' style="color:orange;font-weight:bold;"' : '';
		  		//来店中	
		  		}else{
		  			$search_color = ( $sub_val['status0']==12) ? ' style="color:red;font-weight:bold;"' : '';
		  		}
		  		//前日21時から当日21時までの予約がブルー文字
		  		if($_POST['hope_date'] == date("Y-m-d") && $sub_val['reg_date']>= date("Y-m-d 21:00:00", strtotime("-1 day")) && $sub_val['reg_date']<=date("Y-m-d 21:00:00")){
		  			$search_color = ' style="color:blue;font-weight:bold;"' ;
		  		}

		  		//残金有り
		  		if($sub_val['balance']) $search_color = ' style="color:darkred;font-weight:bold;"' ;

		  		//前、中スペース
		  		$space = $sub_val['hope_time']- $gtimes;
				//if($space)echo str_repeat('<td width="4%" ></td>', $space);
				if($space>0 && $gtimes<$sub_val['hope_time'] ){
					if($space>1){
						$start =$gtimes;
						for ($j=$start; $j < ($sub_val['hope_time']-1); $j++) { 
							if( $staff_shift[$key]=='遅' && $j<4){
								echo '<td style="background-color:black" width="4%" >☓</td>';
							}else{
								$onClick0 ='onClick="whereTo(\''.$url.$param.$j.'&staff_id='.$key.'\',\''.$staff_name.'\',\''.$gTime2[$j].'\');"';	
								echo '<td title="休憩" width="4%" '.$onClick0.'\'"></td>';
							}
							$gtimes++;
						}
					}
					if( $staff_shift[$key]=='遅' && $sub_val['hope_time']<5){
						echo '<td style="background-color:black" width="4%">☓</td>';//
					}else{
						$onClick0 ='onClick="whereTo3(\''.$url.$param.$gtimes.'&staff_id='.$key.'\',\''.$staff_name.'\',\''.$gTime2[$gtimes].'\');"';	
						echo '<td title="休憩" width="4%" '.$onClick0.'\'"></td>';
					}
					$gtimes++;
				}
			
			$start_time = $_POST['hope_date']." ".$gTime2[$sub_val['hope_time']].":00";
			$end_time0 	= $sub_val['hope_time'] + $sub_val['length'];
			$end_time 	= $_POST['hope_date']." ".$gTime4[$end_time0].":00";

			if($sub_val['ctype']==5) $backcolor="#657078"; //テストユーザー
			// 来店時間帯に来店状況がなし
			elseif(strtotime('now')>strtotime($start_time) &&  ($sub_val['type']==1 || $sub_val['type']==2) && $sub_val['status0']==0 )$backcolor="red";
			// ローン延滞者
			elseif($sub_val['loan_delay_flg'] && $sub_val['type']==2) $backcolor="brown";
			// カード引落NG
			elseif(($sub_val['digicat_ng_flg'] || $sub_val['nextpay_end_ng_flg'] || $sub_val['nextpay_end_op_flg']) && $sub_val['type']==2) $backcolor="#995000";
			// 銀行引落NG
			elseif($sub_val['bank_ng_flg'] && $sub_val['type']==2) $backcolor="#99a000";
			// medium violet red.契約（通いホーダイ）
			elseif($sub_val['status0']==15) $backcolor="#c71585";
			// darkred.契約（18回）
			elseif($sub_val['status0']==8) $backcolor="#ff69b4";
			// maroon.契約（15回）
			elseif($sub_val['status0']==7) $backcolor="#cc66ff";
			// saddlebrown.契約（12回）
			elseif($sub_val['status0']==6) $backcolor="#cc99ff";
			// sienna.契約（10回）
			elseif($sub_val['status0']==5) $backcolor="#f08080";
			// chocolate.契約（6回）
			elseif($sub_val['status0']==4) $backcolor="#ffccff";
			// white.契約（3回）
			elseif($sub_val['status0']==13) $backcolor="#ffffff";
			// darkgoldenrod.月額
			elseif($sub_val['status0']==3 || $sub_val['status0']==9) $backcolor="#ccffcc";
			// darkgoldenrod.カスタマイズパック
			elseif($sub_val['status0']==10) $backcolor="#ffe4b5";
			// 未契約
			elseif($sub_val['status0']==2) $backcolor="#f5f5f5";
			// gray.来店なし
			elseif($sub_val['status0']==1) $backcolor="#a9a9a9";
			// 月額引落手続き未処理、乗り換えの場合施術の二回目以後
			elseif($sub_val['course_type'] && !($sub_val['dis_type']==1 && (!$sub_val['r_times'] && !$sub_val['sales_id'] || $sub_val['r_times']==1 && $sub_val['sales_id'] ) ) && $sub_val['pay_type']<2) $backcolor="pink";
			// Gold.スペシャルAKS
			elseif($sub_val['special']==4)    $backcolor="#FFD700";
			// モデル系
			elseif($sub_val['ctype']==3) $backcolor="orange";
			//TGA
			elseif(strstr($sub_val['hope_campaign'],"TGA(全身1回無料)")){$backcolor="#9fd";
				$search_color = ' style="color:#E9546B;font-weight:bold;"' ;
			}

			// 施術予約で来店有り役務消化無し
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['r_times_alarm']) $backcolor="yellow";
			// 来店後、カウンセリング予約で来店のまま
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['type']==1 && $sub_val['status0']==11) $backcolor="yellow";
			// 役職者対応が必要
			elseif($sub_val['sv_flg'] && $sub_val['type']==2) $backcolor="green";
			// 水色.施術,美白
			elseif($sub_val['course_group']==9) $backcolor="#007AAA";
			// LightSkyBlue.施術(トリートメント・通いホーダイ)
			elseif($sub_val['type']==2 && ($sub_val['course_id']==77 || $sub_val['course_id']==1002) ) $backcolor="#1960E3";
			// LightSkyBlue.施術(トリートメント・通常コース)
			elseif($sub_val['type']==2) $backcolor="#87CEFA";

			// hite.カウンセリング
			else $backcolor="white";


				$con_status = "";
				if($sub_val['3dmail_status']) $con_status = $sub_val['3dmail_status'];
				if($sub_val['premail_status']) $con_status .= $sub_val['premail_status'];
				if($sub_val['preday_status']) $con_status .= $sub_val['preday_status'];
				if($sub_val['today_status']) {
					$con_status .= "<font color=";
					switch ($sub_val['today_status']) {
						case '予約時telOK':
							$con_status .="green";
							break;
						case '予約時telﾙｽ':
							$con_status .="yellow";
							break;
						case '予約時telNG':
							$con_status .="red";
							break;
						case 'お客様切電':
							$con_status .="orange";
							break;
					}
					$con_status .= ">(".$sub_val['today_status'].")</font>";
				}

				if(!$sub_val['3dmail_status'] && !$sub_val['premail_status'] && !$sub_val['preday_status'] && !$sub_val['today_status']) $con_status = $sub_val['con_status'];

				$contents = $gTime2[$sub_val['hope_time']]."～".$gTime2[$sub_val['hope_time']+$sub_val['length']]."<br>";
				$contents .= '<font size=-4>'.$sub_val['no'].$sub_val['status'].$sub_val['hope_campaign'].'</font><br>'.$sub_val['name_kana'].'<font size=-4>'.$sub_val['age'].$sub_val['tel'].'<br>'.$con_status.$sub_val['cstaff_id'].$sub_val['memo2'].'</font>';
				if($authority_level<=1 && $sub_val['adcode']){
					$contents .= "<br>(媒体：".$sub_val['adcode'].")" ;
					if( $sub_val['adcode']=="Moba8.net" || $sub_val['adcode']=="アフィリエイトB" ){
						//$contents .= "<br>(参照元：".$sub_val['referer_url'].")" ;
					}
				}
				if($sub_val['loan_status']) $contents .= $sub_val['loan_status'];	
				if($sub_val['rebook_flg']) $contents .= "(再申込)";
				if($sub_val['tstaff_sub1_id']) $contents .= "<br>".$sub_val['tstaff_sub1_id'];	
				if($sub_val['tstaff_sub2_id']) $contents .= "<br>".$sub_val['tstaff_sub2_id'];	

				//予約表示
				if($sub_val['no']=="休憩"){
					$onClick2 ='onClick="whereTo2(\''.$url.$param.$j.'&action=del&id='.$sub_val['id'].'&staff_id='.$key.'\',\''.$staff_name.'\',\''.$gTime2[$j].'\');"';	
					echo '<td title="'.$sub_val['name'].'" colspan="'.$sub_val['length'].'" class="planSample"  '.$onClick2.'\'">'.$contents.'</td>';
				}else{
					echo '<td title="'.$sub_val['name'].'" colspan="'.$sub_val['length'].'" class="planSample" style="background-color:'.$backcolor.'"><a href="../reservation/edit.php?reservation_id='.$sub_val['id'].'" '.$search_color.'>'.$contents.'</a></td>';
				}
				$last_position = $sub_val['hope_time'] + $sub_val['length'];
				$gtimes += $sub_val['length'];
			}

		  	//後ろスペース
		  	$space2 = 21-$last_position;
		  	if($space2>0 ){
				if($space2>1){
					$start = $gtimes;
					for ($k=$start; $k < 20; $k++) { 
						if($gtimes>19)break;
						if($staff_shift[$key]=='早' && $k>17){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}else{
							$onClick0 ='onClick="whereTo(\''.$url.$param.$k.'&staff_id='.$key.'\',\''.$staff_name.'\',\''.$gTime2[$k].'\');"';	
								echo '<td title="休憩" width="4%" '.$onClick0.'\'"></td>';
						}
						$gtimes++;
					}
				}
				if($gtimes<=20){
					if($staff_shift[$key]=='早' && $k>17){
						echo '<td style="background-color:black" width="4%" >☓</td>';
					}else{
						echo '<td width="4%"></td>';//最後の30分枠に新規対象外
					}
				}
				$gtimes++;
			}
		}else{
			//全行空き
			for ($k=1; $k < 20; $k++) { 
				if($staff_shift[$key]=='遅' && $k<4 || $staff_shift[$key]=='早' && $k>17){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}else{
					$onClick0 ='onClick="whereTo(\''.$url.$param.$k.'&staff_id='.$key.'\',\''.$staff_name.'\',\''.$gTime2[$k].'\');"';	
					echo '<td title="休憩" width="4%" '.$onClick0.'\'"></td>';
				}
			}
			if($staff_shift[$key]=='早'){
				echo '<td style="background-color:black" width="4%" >☓</td>';
			}else{
				echo '<td width="4%"></td>';//最後の30分枠に新規対象外
			}
		} 
		echo '</tr>';
		//echo $gtimes."<br />" ;
		$i++;
	}
}

?>
				<tr id="room">
					<th class="table-header-repeat line-left minwidth-1"><a href="">担当名</a> </th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">11</a>	</th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">12</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">13</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">14</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">15</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">16</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">17</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">18</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">19</a></th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">20</a></th>
				</tr>
				</table>
				<!--  end product-table................................... --> 
				</form>
			</div>
			<!--  end content-table  -->
				<!--  start paging..................................................... -->
			<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
			<tr>
				<td style="background-color:#ff69b4">契約18回(<?php echo $cnt['8'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#cc66ff">契約15回(<?php echo $cnt['7'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#cc99ff">契約12回(<?php echo $cnt['6'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#f08080">契約10回(<?php echo $cnt['5'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#ffccff">契約6回(<?php echo $cnt['4'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#ccffcc">契約月額(<?php echo $cnt['3'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#ccffcc">カスタマイズ月額(<?php echo $cnt['9'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#ffe4b5">カスタマイズパック(<?php echo $cnt['10'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#f5f5f5">未契約(<?php echo $cnt['2'];?>)</td><td>&nbsp;</td>
				<td style="background-color:#a9a9a9">来店なし(<?php echo $cnt['1'];?>)</td><td>&nbsp;</td>
				<td style="background-color:white">来店(<?php echo $cnt['11'];?>)</td><td>&nbsp;</td>
				<td style="background-color:white">来店中(<?php echo $cnt['12'];?>)</td><td>&nbsp;</td>
				<td style="background-color:white">カウンセリング</td><td>&nbsp;</td>
				<td style="background-color:#87CEFA">施術</td><td>&nbsp;</td>
				<td style="background-color:#FFD700">スペシャル紹介</td><td>&nbsp;</td>
				<td style="background-color:orange">モデル系</td><td>&nbsp;</td>
				<td style="color:blue;font-weight:bold;">当日予約</td>
				<td style="color:darkred;font-weight:bold;">売掛有り</td>

				<td style="color:green;font-weight:bold;">予約時telOK</td>
				<td style="color:yellow;font-weight:bold;">予約時telﾙｽ</td>
				<td style="color:red;font-weight:bold;">予約時telNG</td>
				<td style="color:orange;font-weight:bold;">お客様切電</td>
			</tr>
			</table>
			<!--  end paging................ -->
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